<h1>Create an account</h1>
<?php $form =  \app\core\form\Form::begin('',"post"); ?>
  <?php echo $form->field($model,'firstname')?>
  <?php echo $form->field($model,'lastname')?>
  <?php echo $form->field($model,'email')?>
  <?php echo $form->field($model,'password')?>
  <?php echo $form->field($model,'passwordConfirm')?>
  <button type="submit" class="btn btn-primary">Submit</button>
<?php \app\core\form\Form::end(); ?>
</form>