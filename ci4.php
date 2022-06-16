layouts/main.php
-------------------------------
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $title; ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">My Application</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="./">Home</a></li>
      <li><a href="<?= base_url('register'); ?>">Register</a></li>
      <li><a href="#">View</a></li>
    </ul>
  </div>
</nav>
    <?= $this->renderSection('content'); ?>
</body>
</html>



///////////////////// Register Controller ////////////////////////
<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
class Register extends BaseController
{
    public function index()
    {
        $data = [];
        $data['title'] = 'Register';
        return view('pages/register',$data);
    }
    public function create(){
        
        $session = \Config\Services::session();     
        $data = [];
        $data['title'] = 'Register';
        helper(['form','url']);
        $val = $this->validate([
            'name' => 'required|min_length[3]',
            'email'=>'required|valid_email|is_unique[userlist.email]',
            'phone' =>'required|numeric|max_length[10]',
            'password' =>'required|max_length[6]'
        ]);
        $model = new UserModel();
        if(!$val)
        {
            echo view('pages/register', $data, [
                'validation'=>$this->validator
            ]);
        }
        else{
            //$data['message'] = "User Register Successfully!";
            $model->save([
                'name' => $this->request->getVar('name'),
                'email'  => $this->request->getVar('email'),
                'phone'  => $this->request->getVar('phone'),
                'password'  => password_hash($this->request->getVar('password'),PASSWORD_DEFAULT),
            ]);
 
            //echo view('pages/register',$data);
            $session->setFlashdata('message', 'User Register Successfully!');
            return redirect()->route('register');
        }
    }
}

//////////////////////////////// Register View ////////////////////
<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<?php helper('form'); ?>
<div class="container">
<?php $validation = \Config\Services::validation(); ?> 
<?php $session = \Config\Services::session(); ?>

    <div class="row">
        <div class="col-md-12">
            <h2>Register</h2>
            <?php if ($session->getFlashdata('message')) { ?>
            <div class="alert alert-success">
                <?= $session->getFlashdata('message') ?>
            </div>
            <?php } ?>
            <form action="<?= base_url('create'); ?>" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" value="<?= set_value('name'); ?>">
                    <?php if($validation->getError('name')) {?>
                        <div class='alert alert-danger mt-2'>
                        <?= $error = $validation->getError('name'); ?>
                        </div>
                    <?php }?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control"  value="<?= set_value('email'); ?>">
                <?php if($validation->getError('email')) {?>
                        <div class='alert alert-danger mt-2'>
                        <?= $error = $validation->getError('email'); ?>
                        </div>
                    <?php }?>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" value="<?= set_value('phone'); ?>">
                <?php if($validation->getError('phone')) {?>
                        <div class='alert alert-danger mt-2'>
                        <?= $error = $validation->getError('phone'); ?>
                        </div>
                    <?php }?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" name="password" id="password" class="form-control" value="<?= set_value('password'); ?>">
                <?php if($validation->getError('password')) {?>
                        <div class='alert alert-danger mt-2'>
                        <?= $error = $validation->getError('password'); ?>
                        </div>
                    <?php }?>
            </div>
            <div class="form-group">
                <label for="password">Confirm Password</label>
                <input type="text" name="cpassword" id="cpassword" class="form-control" value="<?= set_value('cpassword'); ?>">
                <?php if($validation->getError('cpassword')) {?>
                        <div class='alert alert-danger mt-2'>
                        <?= $error = $validation->getError('cpassword'); ?>
                        </div>
                    <?php }?>
            </div>
            <div class="form-group">
               <input type="submit" value="Submit" class="btn btn-primary">
            </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

/////////////////////////////// User Model ///////////////////

<?php 
    namespace App\Models;
    use CodeIgniter\Database\ConnectionInterface;
    use CodeIgniter\Model;

    class UserModel extends Model
    {
    protected $table = 'userlist';
 
    protected $allowedFields = ['name', 'email', 'phone','password'];
    }

?>
