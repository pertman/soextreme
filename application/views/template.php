<?php $this->load->view('head',$headData); ?>

<?php $this->load->view('menu', $topMenu); ?>

<?php $this->load->view('header') ?>
<div class="messages-container">
    <?php $this->load->view('messages',$messages); ?>
</div>
<section class="section">
    <div class="main-container container<?php if (in_array($view, getFullSizePages())):?> full-size<?php endif; ?>">
        <?php $this->load->view($view,$data); ?>
    </div>
</section>

<?php $this->load->view('footer'); ?>
