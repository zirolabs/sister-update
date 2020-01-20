<p class="login-box-msg"><strong>Login</strong> <br/>Masukkan Email dan Password</p>
<form action="javascript:;" class="login-form" id="form-login">
    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Email / Username" name="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>
    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="form-login-result"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-8">
            <div class="checkbox icheck">
                <label>
                    <input type="checkbox" name="ingat_saya" value="1">&nbsp;&nbsp;&nbsp;Ingat Saya
                </label>
            </div>
        </div>
        <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Submit</button>
        </div>
    </div>
</form>
&raquo;&nbsp;&nbsp;<a href="<?=site_url('login/lupa_password')?>">Lupa Password ? </a>

<script type="text/javascript">
    $(document).ready(function(){
        $('#form-login').on('submit',function(){
            $('#form-login-result').html('');
            $.ajax({
                url         : "<?=site_url('login/submit')?>",
                method      : "POST",
                dataType    : "json",
                data        : $('#form-login').serialize(),
                success     : function(result){
                    if(result.status == '201')
                    {
                        $('#form-login-result').html('<div class="alert alert-danger" role="alert"><strong>Kesalahan !</strong><br/>' + result.data + '</div>');
                    }
                    else
                    {
                        $('#form-login-result').html('<div class="alert alert-success" role="alert">' + result.data + '</div>');
                        window.location = "<?=site_url()?>";
                    }
                },
                error       : function(result){
                    $('#form-login-result').html('<?=err_msg('Gagal melakukan login.')?>');
                }
            })
            return false;
        });
    })
</script>
