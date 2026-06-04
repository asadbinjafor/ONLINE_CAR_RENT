<?php
class AdminApiController
{
    private UserModel $users;

    public function __construct()
    {
        $this->users = new UserModel();
    }

    public function deleteMember(): void
    {
        Auth::requireAdmin();
        Security::requireCsrfPost();
        $id = (int) ($_POST['member_id'] ?? 0);
        if ($id <= 0) {
            Security::json(['success' => false, 'message' => 'Invalid member.'], 400);
        }
        if (!$this->users->deleteMember($id)) {
            Security::json(['success' => false, 'message' => 'Member not found or cannot be deleted.'], 404);
        }
        Security::json(['success' => true, 'message' => 'Member deleted.']);
    }
}
