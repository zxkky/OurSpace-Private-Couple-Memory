<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Album Baru - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-md mx-auto px-4 pt-6 pb-24 md:pb-8">
    <h1 class="text-xl font-bold text-rose-50 mb-6">📁 Buat Album Baru</h1>

    <?php if (!empty($error)): ?>
      <div class="bg-red-950/40 text-red-400 text-sm rounded-lg px-3 py-2 mb-4"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/albums/new" class="space-y-4 bg-[#1f1219] p-6 rounded-xl border border-rose-900/40 shadow-sm">
      <div>
        <label class="text-sm text-rose-200">Nama Album</label>
        <input type="text" name="name" required placeholder="Contoh: Liburan Bandung 2026"
          class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/60">
      </div>
      <div>
        <label class="text-sm text-rose-200">Kategori</label>
        <select name="category" class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg">
          <option value="Liburan">Liburan</option>
          <option value="Anniversary">Anniversary</option>
          <option value="Date">Date</option>
          <option value="Random">Random</option>
        </select>
      </div>
      <button type="submit"
        class="w-full bg-rose-500 hover:bg-rose-600 text-white font-medium py-2 rounded-lg transition">
        Buat Album
      </button>
    </form>
  </div>
</body>
</html>