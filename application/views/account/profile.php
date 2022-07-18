<div class="container">
    <h1 class="mt-4 mb-3"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <h1>Ваши данные</h1>
            
            <form action="/account/profile" method="post">
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Логин:</label>
                        <input type="text" class="form-control" value="<?php echo $_SESSION['account']['login']; ?>" disabled>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>E-mail:</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['email']; ?>" disabled>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Номер</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['wallet']; ?>" disabled>
                    </div>
                </div>
                <!--<div class="control-group form-group">
                    <div class="controls">
                        <label>Новый пароль для входа:</label>
                        <input type="password" class="form-control" name="password" disabled>
                    </div>
                </div>-->
                <!--<button type="submit" class="btn btn-primary">Сохранить</button>-->
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Имя </label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['name']; ?>" disabled>
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Фамилия</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['lastname']; ?>" disabled>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>