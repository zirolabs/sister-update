<p class="login-box-msg"><strong>Lupa Password</strong> <br/>Masukkan Email dan Password baru</p>
<form action="javascript:;" class="login-form" id="form-lupa-password">
    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password baru" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Ulangi Password Baru" name="cpassword">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="form-lupa-password-result"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-4 col-xs-offset-8">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
        </div>
    </div>
</form>
<a href="<?=site_url('login')?>">&laquo; Kembali ke halaman login</a>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form-lupa-password').on('submit',function(){
            $('#form-lupa-password-result').html('');
            $.ajax({
                url         : "<?=site_url('login/lupa_password/submit')?>",
                method      : "POST",
                dataType    : "json",
                data        : $('#form-lupa-password').serialize(),
                success     : function(result){
                    if(result.status == '201')
                    {
                        $('#form-lupa-password-result').html('<div class="alert alert-danger" role="alert"><strong>Kesalahan !</strong><br/>' + result.data + '</div>');
                    }
                    else
                    {
                        $('#form-lupa-password-result').html('<div class="alert alert-success" role="alert">' + result.data + '</div>');                    
                        window.location = "<?=site_url('login')?>";
                    }
                },
                error       : function(result){
                    $('#form-lupa-password-result').html('<?=err_msg('Gagal melakukan permintaan reset password.')?>');
                }
            })
            return false;
        });        
    })
</script>        