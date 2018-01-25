<?php
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'size'	=> 30,
	'value' => set_value('username')
);

$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30
);

$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0'
);

$confirmation_code = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8
);

 echo form_open($this->uri->uri_string())?>

<?php echo $this->dx_auth->get_auth_error(); ?>


<div>
    <div class="form-group">
        <dt><?php echo form_label('Username', $username['id']);?></dt>
        <dd>
            <?php echo form_input($username)?>
        <?php echo form_error($username['name']); ?>
        </dd>
    </div>
    <div class="form-group">
        <dt><?php echo form_label('Password', $password['id']);?></dt>
        <dd>
            <?php echo form_password($password)?>
        <?php echo form_error($password['name']); ?>
        </dd>
    </div>

    <div class="form-group">
		<?php echo form_checkbox($remember);?> <?php echo form_label('Remember me', $remember['id']);?> 
		<?php echo anchor($this->dx_auth->forgot_password_uri, 'Forgot password');?> 
		<?php
			if ($this->dx_auth->allow_registration) {
				echo anchor($this->dx_auth->register_uri, 'Register');
			};
		?>
	</div>

	<dt></dt>
	<dd><?php echo form_submit('login','Login');?></dd>
</div>

<?php echo form_close()?>
</fieldset>
