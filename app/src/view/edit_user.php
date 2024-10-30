<form method="POST" action="<?= $action ?>">
    <div>
        <label for="name">Name</label>
        <input type="text" name="user[name]" id="name" autofocus>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="text" name="user[email]" id="email">
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="user[password]" id="password">
    </div>
    <button type="submit">Submit</button>
</form>