<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Album - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-5xl mx-auto px-4 pt-6 pb-24 md:pb-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-rose-50">📁 Album Kenangan</h1>
      <a href="/albums/new" class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Album Baru
      </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php foreach ($albums as $a): ?>
        <a href="/albums/<?= h((string) $a['id']) ?>" class="bg-[#1f1219] rounded-xl overflow-hidden border border-rose-900/40 shadow-sm hover:shadow-md transition">
          <div class="aspect-square bg-rose-950/40 flex items-center justify-center">
            <?php if (!empty($a['cover'])): ?>
              <img src="/uploads/<?= h($a['cover']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
            <?php else: ?>
              <span class="text-3xl">📷</span>
            <?php endif; ?>
          </div>
          <div class="p-3">
            <p class="font-medium text-rose-50 text-sm truncate"><?= h($a['name']) ?></p>
            <p class="text-xs text-rose-300/90"><?= h($a['category']) ?> · <?= h((string) $a['photo_count']) ?> item</p>
          </div>
        </a>
      <?php endforeach; ?>
      <?php if (count($albums) === 0): ?>
        <p class="text-rose-400/60 text-sm col-span-4">Belum ada album. Buat album pertama kalian!</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>