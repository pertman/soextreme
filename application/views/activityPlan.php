

<div class="container">
    <?php
    //@TODO WIP
    if (isset($activity)) :
        //var_dump($activity)?>
    <div class="activity">
        <div class="field">
            <label for="act_title">Titre</label>
            <div class="control">
                <input class="input" value="<?php echo $activity[0]["act_name"]; ?>" type="text" name="act_name" readonly>
            </div>
        </div>
        <div class="field">
            <label for="act_description">Description</label>
            <div class="control">
                <textarea class="textarea"  name="act_description" placeholder="Description de l'activité..." readonly><?php echo $activity[0]["act_description"]; ?></textarea>
            </div>
        </div>
        <div class="field">
            <label for="act_resume">Description courte</label>
            <div class="control">
                <textarea class="textarea" name="act_resume" placeholder="Description courte de l'activité..." readonly><?php echo $activity[0]["act_resume"]; ?></textarea>
            </div>
        </div>
        <div class="field">
            <label for="act_base_price">Prix hors promotion (€)</label>
            <div class="control">
                <input class="input" value="<?php echo $activity[0]["act_base_price"]; ?>" min="0.00" step="0.01" name="act_base_price" readonly>
            </div>
        </div>
        <div class="field">
            <label for="act_duration">Durée d'une session (min)</label>
            <div class="control">
                <input class="input" value="<?php echo $activity[0]["act_duration"]; ?>" name="act_duration" readonly>
            </div>
        </div>
        <div class="field">
            <label for="act_monitor_nb">Nombre de moniteur requis</label>
            <div class="control">
                <input class="input" value="<?php echo $activity[0]["act_monitor_nb"]; ?>" min="0" name="act_monitor_nb" readonly>
            </div>
        </div>
        <div class="field">
            <label for="act_operator_nb">Nombre d'opérateur requis</label>
            <div class="control">
                <input class="input" type="number" value="<?php echo $activity[0]["act_operator_nb"]; ?>" min="0" name="act_operator_nb" readonly>
            </div>
        </div>
        <div class="field">
            <label class="checkbox">
                L'activité fait partie d'une offre spéciale ?
                <input type="checkbox" name="act_is_special_offer">
            </label>
        </div>
        <div class="field">
            <div class="control">
                <textarea class="textarea" name="act_description_special_offer" placeholder="Description de l'offre spéciale..."></textarea>
            </div>
        </div>



        <div class="field">

        </div>
    </div>
    <div class="calendar">
        <form method="post" action="scheduleActivity">
        <div class="field">
        Début
        <input type="date" name="date_start">
        Fin
        <input type="date" name="date_end">
        </div>
        <div class="field">
        Jours :
            <label class="checkbox">
                Lundi
                <input type="checkbox" name="monday">
            </label>
            <label class="checkbox">
                Mardi
                <input type="checkbox" name="tuesday">
            </label>
            <label class="checkbox">
                Mercredi
                <input type="checkbox" name="wednesday">
            </label>
            <label class="checkbox">
                Jeudi
                <input type="checkbox" name="thursday">
            </label>
            <label class="checkbox">
                Vendredi
                <input type="checkbox" name="friday">
            </label>
            <label class="checkbox">
                Samedi
                <input type="checkbox" name="saturday">
            </label>
            <label class="checkbox">
                Dimanche
                <input type="checkbox" name="sunday">
            </label>
        </div>
        <div class="field">
            Heure Début
            <input type="time" name="hour_start">
            Heure Fin
            <input type="time" name="hour_end">
        </div>
            <div class="field">
                <input type="hidden" name="act_id" value="<?php echo $activity[0]["act_id"]; ?>">
                <div class="control">
                    <button class="button is-link">Valider</button>
                </div>
            </div>
    </div>
    <?php endif; ?>
</div>


