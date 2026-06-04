<?php
class AuthController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function showLogin(): void
    {
        if (Auth::user()) {
            redirect('/');
        }
        view('auth/login', ['title' => 'Login', 'errors' => []]);
    }

    public function showRegister(): void
    {
        if (Auth::user()) {
            redirect('/');
        }
        view('auth/register', ['title' => 'Register', 'errors' => []]);
    }

    public function login(): void
    {
        Security::requireCsrfPost();
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = !empty($_POST['remember_me']);
        $errors = [];
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        }
        if ($password === '') {
            $errors['password'] = 'Password required.';
        }
        if ($errors) {
            view('auth/login', ['title' => 'Login', 'errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }
        $user = $this->users->findByEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors['general'] = 'Invalid email or password.';
            view('auth/login', ['title' => 'Login', 'errors' => $errors, 'old' => ['email' => $email]]);
            return;
        }
        Auth::login($user);
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            $this->users->setRememberToken((int) $user['id'], $token);
            Auth::setRemember((int) $user['id'], $token);
        }
        redirect('/');
    }

    public function register(): void
    {
        Security::requireCsrfPost();
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'member';
        if (!in_array($role, ['admin', 'member'], true)) {
            $role = 'member';
        }
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        } elseif ($this->users->emailExists($email)) {
            $errors['email'] = 'Email already registered.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($password !== $confirm) {
            $errors['password_confirm'] = 'Passwords do not match.';
        }
        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }
        if ($phone === '' || !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
            $errors['phone'] = 'Valid phone number required.';
        }
        if ($errors) {
            view('auth/register', [
                'title' => 'Register',
                'errors' => $errors,
                'old' => compact('name', 'email', 'role', 'address', 'phone'),
            ]);
            return;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $this->users->create($name, $email, $hash, $role, $address, $phone);
        flash('success', 'Registration successful. Please log in.');
        redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }
}
