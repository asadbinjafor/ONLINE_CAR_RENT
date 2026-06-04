<h1 class="section-title">Manage Members</h1>
<div class="table-wrap card">
    <table class="data-table" id="members-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Joined</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($members)): ?>
                <tr><td colspan="5">No members found.</td></tr>
            <?php else: ?>
                <?php foreach ($members as $m): ?>
                    <tr id="member-row-<?= (int) $m['id'] ?>">
                        <td><?= Security::e($m['name']) ?></td>
                        <td><?= Security::e($m['email']) ?></td>
                        <td><?= Security::e($m['phone'] ?? '—') ?></td>
                        <td><?= Security::e(date('M j, Y', strtotime($m['created_at']))) ?></td>
                        <td>
                            <button type="button" class="btn btn-sm danger" data-delete-member="<?= (int) $m['id'] ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<script src="<?= BASE_URL ?>/public/js/admin.js"></script>
