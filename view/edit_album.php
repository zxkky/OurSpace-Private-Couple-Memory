<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Edit Album - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-md mx-auto px-4 pt-6 pb-24 md:pb-8">
    <a href="/albums/<?= h((string) $album['id']) ?>" class="text-sm text-rose-400">← Kembali</a>
    <h1 class="text-xl font-bold text-rose-50 mt-2 mb-6">✏️ Edit Album</h1>

    <?php if (!empty($error)): ?>
      <div class="bg-red-950/40 text-red-400 text-sm rounded-lg px-3 py-2 mb-4"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/albums/<?= h((string) $album['id']) ?>/edit" class="space-y-4 bg-[#1f1219] p-6 rounded-xl border border-rose-900/40 shadow-sm">
      <div>
        <label class="text-sm text-rose-200">Nama Album</label>
        <input type="text" name="name" required value="<?= h($album['name']) ?>"
          class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/60">
      </div>
      <div>
        <label class="text-sm text-rose-200">Kategori</label>
        <select name="category" class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg">
          <?php foreach (['Liburan', 'Anniversary', 'Date', 'Random'] as $cat): ?>
            <option value="<?= h($cat) ?>" <?= $album['category'] === $cat ? 'selected' : '' ?>><?= h($cat) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit"
        class="w-full bg-rose-500 hover:bg-rose-600 text-white font-medium py-2 rounded-lg transition">
        Simpan Perubahan
      </button>
    </form>
  </div>
</body>
</html>