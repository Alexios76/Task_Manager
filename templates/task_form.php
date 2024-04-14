<?php
// Assumer que $task est défini si c'est une modification, sinon c'est une création
$isEditing = isset($task);
$actionUrl = $isEditing ? "update_task.php" : "create_task.php";
$buttonLabel = $isEditing ? "Mettre à jour" : "Créer";
?>

<div class="container">
    <h2><?= $isEditing ? "Modifier Tâche" : "Créer Nouvelle Tâche"; ?></h2>
    <form action="<?= htmlspecialchars($actionUrl); ?>" method="post">
        <div class="mb-3">
            <label for="title" class="form-label">Titre de la tâche</label>
            <input type="text" class="form-control" id="title" name="title" value="<?= $isEditing ? htmlspecialchars($task['title']) : ''; ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?= $isEditing ? htmlspecialchars($task['description']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="assigned_to" class="form-label">Assigner à</label>
            <select class="form-control" id="assigned_to" name="assigned_to">
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id']; ?>" <?= $isEditing && $user['id'] == $task['assigned_to'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($isEditing): ?>
            <input type="hidden" name="task_id" value="<?= htmlspecialchars($task['id']); ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?= $buttonLabel; ?></button>
    </form>
</div>
