<?php
class ProfileController
{
    private UserModel $users;
    private OrderModel $orders;

    public function __construct()
    {
        $this->users = new UserModel();
        $this->orders = new OrderModel();
    }

    public function show(): void
    {
        Auth::requireRole('admin', 'member');
        $user = $this->users->findById(Auth::user()['id']);
        $rentalHistory = Auth::user()['role'] === 'member' ? $this->orders->forUser($user['id']) : [];
        view('profile/show', [
            'title' => 'My Profile',
            'user' => $user,
            'rentalHistory' => $rentalHistory,
            'errors' => [],
            'success' => flash('success'),
        ]);
    }

    public function update(): void
    {
        Auth::requireRole('admin', 'member');
        Security::requireCsrfPost();
        $uid = Auth::user()['id'];
        $user = $this->users->findById($uid);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $current = $_POST['current_password'] ?? '';
        $newPass = $_POST['new_password'] ?? '';
        $confirm = $_POST['new_password_confirm'] ?? '';
        $errors = [];

        if ($name === '') {
            $errors['name'] = 'Name is required.';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email required.';
        } elseif ($this->users->emailExists($email, $uid)) {
            $errors['email'] = 'Email already in use.';
        }
        if ($address === '') {
            $errors['address'] = 'Address is required.';
        }
        if ($phone === '' || !preg_match('/^[0-9+\-\s]{6,20}$/', $phone)) {
            $errors['phone'] = 'Valid phone number required.';
        }

        $picture = $user['profile_picture'];
        if (!empty($_FILES['profile_picture']['name'])) {
            $uploadErr = Security::validateImageUpload($_FILES['profile_picture']);
            if ($uploadErr) {
                $errors['profile_picture'] = $uploadErr;
            } else {
                $saved = Security::saveUpload($_FILES['profile_picture'], PROFILE_UPLOAD_DIR, 'profile');
                if ($saved) {
                    $picture = $saved;
                } else {
                    $errors['profile_picture'] = 'Could not save image.';
                }
            }
        }

        if ($newPass !== '' || $confirm !== '' || $current !== '') {
            if (!password_verify($current, $user['password_hash'])) {
                $errors['current_password'] = 'Current password is incorrect.';
            } elseif (strlen($newPass) < 8) {
                $errors['new_password'] = 'New password must be at least 8 characters.';
            } elseif ($newPass !== $confirm) {
                $errors['new_password_confirm'] = 'Passwords do not match.';
            }
        }

        if ($errors) {
            $rentalHistory = Auth::user()['role'] === 'member' ? $this->orders->forUser($uid) : [];
            view('profile/show', [
                'title' => 'My Profile',
                'user' => $user,
                'rentalHistory' => $rentalHistory,
                'errors' => $errors,
                'success' => null,
            ]);
            return;
        }

        $this->users->updateProfile(
            $uid,
            $name,
            $email,
            $address,
            $phone,
            $picture !== $user['profile_picture'] ? $picture : null
        );
        if ($newPass !== '' && password_verify($current, $user['password_hash'])) {
            $this->users->updatePassword($uid, password_hash($newPass, PASSWORD_DEFAULT));
        }
        $_SESSION['name'] = $name;
        flash('success', 'Profile updated successfully.');
        redirect('/profile');
    }
}
