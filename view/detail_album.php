<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => $album['name'] . ' - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-5xl mx-auto px-4 pt-6 pb-24 md:pb-8">
    <div class="mb-6">
      <a href="/albums" class="text-sm text-rose-400">← Semua Album</a>
      <div class="flex items-start justify-between mt-1 gap-3">
        <div class="min-w-0">
          <h1 class="text-xl font-bold text-rose-50 truncate"><?= h($album['name']) ?></h1>
          <p class="text-sm text-rose-300/90"><?= h($album['category']) ?></p>
        </div>
        <div class="flex gap-2 shrink-0">
          <a href="/albums/<?= h((string) $album['id']) ?>/edit"
            class="text-xs font-medium px-3 py-1.5 rounded-lg border border-rose-900/40 text-rose-200 hover:bg-[#1c0f16]">Edit</a>
          <form method="POST" action="/albums/<?= h((string) $album['id']) ?>/delete"
            onsubmit="return confirm('Hapus album ini beserta semua foto/video di dalamnya? Aksi ini tidak bisa dibatalkan.');">
            <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-900/50 text-red-400 hover:bg-red-950/40">Hapus</button>
          </form>
        </div>
      </div>
    </div>

    <details class="mb-6 bg-[#1f1219] rounded-xl border border-rose-900/40 shadow-sm">
      <summary class="cursor-pointer px-5 py-3 font-medium text-rose-400 text-sm">+ Upload Foto/Video</summary>
      <form method="POST" action="/albums/<?= h((string) $album['id']) ?>/upload" enctype="multipart/form-data"
        class="px-5 pb-5 space-y-3">
        <input type="file" name="media" accept="image/*,video/*" required
          class="w-full text-sm">
        <input type="text" name="caption" placeholder="Caption (opsional)"
          class="w-full px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <input type="text" name="location" placeholder="Lokasi (opsional)"
            class="px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
          <input type="date" name="taken_at"
            class="px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
        </div>
        <button type="submit" class="w-full sm:w-auto bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
          Upload
        </button>
      </form>
    </details>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      <?php foreach ($photos as $p): ?>
        <div class="relative aspect-square rounded-lg overflow-hidden bg-[#241420] group">
          <a href="/photos/<?= h((string) $p['id']) ?>" class="block w-full h-full">
            <?php if ($p['media_type'] === 'video'): ?>
              <video src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)"></video>
              <span class="absolute top-1 right-1 text-white text-xs bg-black/50 rounded px-1">🎥</span>
            <?php else: ?>
              <img src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
            <?php endif; ?>
            <div class="absolute bottom-0 left-0 right-0 bg-black/40 text-white text-xs px-2 py-1 flex justify-between">
              <span>❤️ <?= h((string) $p['like_count']) ?></span>
              <span>💬 <?= h((string) $p['comment_count']) ?></span>
            </div>
          </a>
        </div>
      <?php endforeach; ?>
      <?php if (count($photos) === 0): ?>
        <p class="text-rose-400/60 text-sm col-span-4">Belum ada foto/video di album ini.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>