<?php $this->load->view('header',$headerData); ?>

<div class="messages-container">
    <?php $this->load->view('messages',$messages); ?>
</div>
<div class="main-container">
    <?php $this->load->view($view,$data); ?>
</div>

<?php $this->load->view('footer'); ?>
