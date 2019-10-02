<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require 'application/libraries/REST_Controller.php';
class ApiController extends REST_Controller
{

    protected $_params;

    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        $this->load->database();
        $this->load->helper('main_helper');
        $this->load->helper('planning_helper');
        $this->load->model('UserModel');
        $this->load->model('ActivityModel');
        $this->load->model('CategoryModel');
        $this->load->model('AdminModel');
        $this->load->model('MenuModel');
        $this->load->model('PlanningModel');
        $this->load->model('ReservationModel');
        $this->load->model('TicketModel');
        $this->load->model('PromotionModel');
        $this->load->model('PaymentModel');
        $this->load->model('CommentModel');
        $this->load->model('NewsletterModel');
        $this->load->model('AdminRequestModel');
    }

    protected $_dayIndexLabelMapping = array(
        '1' => 'monday',
        '2' => 'tuesday',
        '3' => 'wednesday',
        '4' => 'thursday',
        '5' => 'friday',
        '6' => 'saturday',
        '7' => 'sunday'
    );

    //Gestion activités
    function Activities_get()
    {
        $actTerm = $this->input->get('term');
        if ($actTerm) {
            $activities = $this->ActivityModel->getActivityByTerm($actTerm);
        } else {
            $activities = $this->ActivityModel->getAllActivities();
        }

        die(json_encode($activities));
    }

    function ActivityById_get()
    {
        $actId = $this->input->get('id');
        if (!$actId) {
            die('Error id parameter missing');
        }
        $activities = $this->ActivityModel->getActivityById($actId);
        die(json_encode($activities));
    }

    function FamousActivities_get()
    {
        $activities = $this->ActivityModel->getFamousActivities();
        die(json_encode($activities));
    }

    //Gestion réservation
    function ReservationsByUserId_get()
    {
        $usrId = $this->input->get('id');
        $activities = $this->ReservationModel->getReservationsByUserId($usrId);
        die(json_encode($activities));
    }
    function TicketByResId_get(){
        $resId = $this->input->get('id');
        $activities = $this->TicketModel->getTicketsByReservationId($resId);
        die(json_encode($activities));
    }


    public function seeActivityPlanning_get()
    {
        $actId = $this->input->get('id');
        $activity = $this->ActivityModel->getActivityById($actId);
        $activityDate = array();

        $todayDateTime = new DateTime();
        $todayDateTime->setTime(0, 0, 0);

        $allPlanningItemsForActivity = $this->PlanningModel->getAllPlanningItemsForActivity($activity['act_id']);

        foreach ($allPlanningItemsForActivity as $planningItem) {
            $plaId = $planningItem['pla_id'];
            $periodStart = $planningItem['pla_date_start'];
            $periodEnd = $planningItem['pla_date_end'];
            $dayIndex = $planningItem['tsl_day_index'];
            $startHour = $planningItem['tsl_hour_start'];
            $endHour = $planningItem['tsl_hour_end'];
            $tslId = $planningItem['tsl_id'];

            $planningItemResult = $this->getIndexDayInRange($periodStart, $periodEnd, $dayIndex);

            $slots = $this->getSessionsBetweenHours($startHour, $endHour, $activity);

            foreach ($planningItemResult as $date) {

                $planningDateTime = new DateTime($date);

                if ($planningDateTime < $todayDateTime) {
                    continue;
                }
                $reservedSlots = $this->getDateReservedSlots($date, $activity['act_id']);

                $dateSlots = $this->applyPromotionsToDateSlots($slots, $date, $startHour, $endHour, $activity);

                foreach ($reservedSlots as $time => $participantNb) {
                    //Remove reserved time slots but on hours not available because of planning modifications
                    if (isset($dateSlots[$time])) {
                        $dateSlots[$time]['participantNb'] -= $participantNb;
                    }
                }

                //Events needs autoincrement index
                $dateSlots = array_values($dateSlots);

                $dateData = array(
                    'date' => $date,
                    'start' => $startHour,
                    'end' => $endHour,
                    'pla_id' => $plaId,
                    'tsl_id' => $tslId,
                    'slots' => $dateSlots,
                );

                $activityDate[] = $dateData;
            }
        }
        $result = array();
        //$result['activity']  = $activity;
        die(json_encode($activityDate));
    }

    public function getIndexDayInRange($periodStart, $periodEnd, $dayIndex)
    {
        $dateFrom = new \DateTime($periodStart);
        $dateTo = new \DateTime($periodEnd);
        $dates = array();

        if ($dateFrom > $dateTo) {
            return $dates;
        }

        $dayLabel = $this->_dayIndexLabelMapping[$dayIndex];

        if ($dayIndex != $dateFrom->format('N')) {
            $dateFrom->modify('next ' . $dayLabel);
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format('Y-m-d');
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }

    private function getSessionsBetweenHours($startHour, $endHour, $activity)
    {
        $slots = array();
        $duration = $activity['act_duration'];
        $actParticipantNb = $activity['act_participant_nb'];
        $actBasePrice = $activity['act_base_price'];

        $time = $startHour;

        while ($time < $endHour) {
            $index = substr_replace($time, "", -3);

            $slots[$index]['start'] = $time;
            $time = addMinutesToTime($time, $duration);
            $slots[$index]['end'] = $time;

            $slots[$index]['participantNb'] = $actParticipantNb;

            $slots[$index]['base_price'] = $actBasePrice;
            $slots[$index]['price'] = $actBasePrice;

            if ($time > $endHour) {
                unset($slots[$index]);
                break;
            }
        }

        return $slots;
    }

    public function getDateReservedSlots($date, $actId)
    {
        $reservedSlots = array();
        $dateReservations = $this->ReservationModel->getReservationsByDate($date, $actId);

        if ($dateReservations) {
            foreach ($dateReservations as $dateReservation) {
                $timeArray = explode('-', $dateReservation['res_time_slot']);
                $startTime = $timeArray[0];

                if (isset($reservedSlots[$startTime])) {
                    $reservedSlots[$startTime] += $dateReservation['res_participant_nb'];
                } else {
                    $reservedSlots[$startTime] = $dateReservation['res_participant_nb'];
                }
            }
        }

        return $reservedSlots;
    }

    public function applyPromotionsToDateSlots($dateSlots, $date, $startHour, $endHour, $activity)
    {
        $datePromotions = $this->PromotionModel->getDateAndHoursPromotion($date, $startHour, $endHour);

        foreach ($dateSlots as $timeKey => $dateSlot) {
            $dateSlots[$timeKey]['base_price'] = formatPrice($dateSlots[$timeKey]['price']);

            $priorities = array();

            foreach ($datePromotions as $datePromotion) {

                if ($datePromotion['pro_act_ids']) {
                    $actIds = explode(',', $datePromotion['pro_act_ids']);
                    if (!in_array($activity['act_id'], $actIds)) {
                        continue;
                    }
                }

                if ($datePromotion['pro_cat_ids']) {
                    $catIds = explode(',', $datePromotion['pro_cat_ids']);
                    if (!in_array($activity['cat_id'], $catIds)) {
                        continue;
                    }
                }

                if ($datePromotion['pro_hour_start'] && $dateSlot['start'] < $datePromotion['pro_hour_start']
                    || $datePromotion['pro_hour_end'] && $dateSlot['end'] > $datePromotion['pro_hour_end']) {
                    continue;
                }

                $priorityPrice = null;

                if ($discountAmount = $datePromotion['pro_discount_fix']) {
                    $priorityPrice = $dateSlots[$timeKey]['price'] - $discountAmount;
                }
                if ($discountPercent = $datePromotion['pro_discount_percent']) {
                    $priorityPrice = $dateSlots[$timeKey]['price'] * (1 - $discountPercent * 0.01);
                }

                if (!isset($priorities[$timeKey][$datePromotion['pro_priority']])) {
                    $priorities[$timeKey][$datePromotion['pro_priority']]['pro_id'] = $datePromotion['pro_id'];
                    $priorities[$timeKey][$datePromotion['pro_priority']]['price'] = $priorityPrice;
                    $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['name'] = $datePromotion['pro_name'];

                    if ($discountAmount = $datePromotion['pro_discount_fix']) {
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount'] = $discountAmount;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent'] = null;
                    }
                    if ($discountPercent = $datePromotion['pro_discount_percent']) {
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount'] = null;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent'] = $discountPercent;
                    }

                } else {
                    if ($priorityPrice < $priorities[$timeKey][$datePromotion['pro_priority']]['price']) {
                        $priorities[$timeKey][$datePromotion['pro_priority']]['pro_id'] = $datePromotion['pro_id'];
                        $priorities[$timeKey][$datePromotion['pro_priority']]['price'] = $priorityPrice;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['name'] = $datePromotion['pro_name'];

                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount'] = null;
                        $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent'] = null;

                        if ($discountAmount = $datePromotion['pro_discount_fix']) {
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount'] = $discountAmount;
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent'] = null;
                        }
                        if ($discountPercent = $datePromotion['pro_discount_percent']) {
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_amount'] = null;
                            $priorities[$timeKey][$datePromotion['pro_priority']][$datePromotion['pro_id']]['discount_percent'] = $discountPercent;
                        }
                    }
                }
            }

            foreach ($priorities as $priorityTimeKey => $priority) {
                foreach ($priority as $priorityKey => $priorityPromotions) {
                    if ($discountAmount = $priorityPromotions[$priorityPromotions['pro_id']]['discount_amount']) {
                        $dateSlots[$timeKey]['price'] = $dateSlots[$timeKey]['price'] - $discountAmount;
                    }
                    if ($discountPercent = $priorityPromotions[$priorityPromotions['pro_id']]['discount_percent']) {
                        $dateSlots[$timeKey]['price'] = $dateSlots[$timeKey]['price'] * (1 - $discountPercent * 0.01);
                    }
                    $dateSlots[$timeKey]['promotions'][$priorityKey]['pro_id'] = $priorityPromotions['pro_id'];
                    $dateSlots[$timeKey]['promotions'][$priorityKey]['pro_name'] = $priorityPromotions[$priorityPromotions['pro_id']]['name'];
                }

            }

            $dateSlots[$timeKey]['price'] = formatPrice($dateSlots[$timeKey]['price']);
        }

        return $dateSlots;
    }

    //Gestion utilisateur
    public function UserById_get($id){
        $usrId = $this->input->get('id');
        $user = $this->UserModel->getUserById($usrId);
        die(json_encode($user));
    }

    public function connect()
    {
        if ($post = $this->input->post()) {

            $user = $this->UserModel->getUserByEmail($post['usr_email']);

            if (!$user) {
                $this->_params['messages'][] = 'Cet email n\'est pas enregistré';
                return $this->load->view('template.php', $this->_params);
            }

            if (sha1($post['usr_password']) != $user['usr_password']) {
                $this->_params['messages'][] = 'Le mot de passe ne correspond pas';
                return $this->load->view('template.php', $this->_params);
            }

            $_SESSION['user']['id'] = $user['usr_id'];
            $_SESSION['messages'][] = 'Connexion réussie';

            $this->redirectHome();
        } else {
            $this->load->view('template.php', $this->_params);
        }


    }


    //Gestion promotions
    public function promotion_get()
    {
        $actId = $this->input->get('id');
        $promotions = $this->PromotionModel->getAllPromotionsById($actId);
        die(json_encode($promotions));
    }


    //Gestion commentaires
    public function comment_get()
    {
        $actId = $this->input->get('id');
        $comments = $this->CommentModel->getFirstLevelComments($actId);
        die(json_encode($comments));
    }

    public function lastComment_get()
    {
        $comments = $this->CommentModel->get4LastLevelComments();
        die(json_encode($comments));
    }

    //Gestion utilisateur
    public function login_get()
    {
        $mail = $this->input->get('email');
        $password = $this->input->get('password');
        $user = $this->UserModel->getUserByEmail($mail);

        if (!$user){
            $this->_params['messages'][] = 'Cet email n\'est pas enregistré';
            //return $this->load->view('template.php', $this->_params);
        }

        if (sha1($password) == $user['usr_password']){
            //$this->_params['messages'][] = 'Le mot de passe ne correspond pas';
            //return $this->load->view('template.php', $this->_params);
            die(json_encode($user));
        }

        //$_SESSION['user']['id']     = $user['usr_id'];
        //$_SESSION['messages'][]     = 'Connexion réussie';


    }
}