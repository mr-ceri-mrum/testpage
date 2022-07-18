<div class="container">
    <h1 class="mt-4 mb-3"><?php echo $title; ?></h1>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <h4>Профиля</h4>
            
            <form action="/account/profile/edit" method="post">
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
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['email']; ?>" name="email">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Номер</label>
                        <input type="text" class="form-control"  value="<?php echo $_SESSION['account']['wallet']; ?>" name="wallet">
                    </div>
                </div>
                <div class="control-group form-group">
                    <div class="controls">
                        <label>Новый пароль для входа:</label>
                        <input type="password" class="form-control" name="password" />
                    </div>
                </div>
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
                <button type="submit" class="btn btn-primary">Сохранить</button>
                <a href="/account/logout"> Выход</a>
            </form>
        </div>
    </div>
</div>