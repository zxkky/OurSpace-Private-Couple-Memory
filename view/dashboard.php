<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Dashboard - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-5xl mx-auto px-4 pt-6 pb-24 md:pb-8">

    <div class="bg-gradient-to-br from-rose-600 to-rose-900 text-white rounded-2xl p-6 sm:p-8 mb-8 shadow-lg">
      <p class="text-rose-100 text-sm mb-1">❤️ Bersama Selama</p>
      <p class="font-serif text-4xl sm:text-5xl font-bold tracking-wide"><?= formatNumberId($bersama) ?> Hari</p>
      <p class="text-rose-100 text-sm mt-4">🎉 Anniversary tahun ini dalam <span class="font-semibold text-white"><?= h((string) $countdown) ?> hari</span></p>
    </div>

    <div class="grid grid-cols-3 gap-2 sm:gap-4 mb-8">
      <div class="bg-[#1f1219] rounded-xl p-3 sm:p-5 text-center border border-rose-900/40 shadow-sm">
        <p class="text-xl sm:text-2xl font-bold text-rose-50"><?= h((string) $totalPhotos) ?></p>
        <p class="text-[11px] sm:text-xs text-rose-300/90 mt-1">📷 Total Foto</p>
      </div>
      <div class="bg-[#1f1219] rounded-xl p-3 sm:p-5 text-center border border-rose-900/40 shadow-sm">
        <p class="text-xl sm:text-2xl font-bold text-rose-50"><?= h((string) $totalVideos) ?></p>
        <p class="text-[11px] sm:text-xs text-rose-300/90 mt-1">🎥 Total Video</p>
      </div>
      <div class="bg-[#1f1219] rounded-xl p-3 sm:p-5 text-center border border-rose-900/40 shadow-sm">
        <p class="text-xl sm:text-2xl font-bold text-rose-50"><?= h((string) $totalAlbums) ?></p>
        <p class="text-[11px] sm:text-xs text-rose-300/90 mt-1">📁 Album</p>
      </div>
    </div>

    <?php if (count($onThisDay) > 0): ?>
      <div class="bg-amber-950/30 border border-amber-800/50 rounded-xl p-5 mb-8">
        <p class="font-semibold text-amber-300 mb-3">❤️ Hari Ini Tahun Lalu</p>
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
          <?php foreach ($onThisDay as $p): ?>
            <a href="/photos/<?= h((string) $p['id']) ?>" class="block aspect-square rounded-lg overflow-hidden bg-[#241420]">
              <img src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <div class="flex items-center justify-between mb-4">
      <h2 class="font-semibold text-rose-50">🔥 Kenangan Terbaru</h2>
      <a href="/albums" class="text-sm text-rose-400">Lihat semua album →</a>
    </div>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
      <?php foreach ($recentPhotos as $p): ?>
        <a href="/photos/<?= h((string) $p['id']) ?>" class="block aspect-square rounded-lg overflow-hidden bg-[#241420] relative group">
          <?php if ($p['media_type'] === 'video'): ?>
            <video src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)"></video>
            <span class="absolute bottom-1 right-1 text-white text-xs bg-black/50 rounded px-1">🎥</span>
          <?php else: ?>
            <img src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
          <?php endif; ?>
        </a>
      <?php endforeach; ?>
      <?php if (count($recentPhotos) === 0): ?>
        <p class="text-rose-400/60 text-sm col-span-6">Belum ada kenangan. Yuk upload foto pertama kalian di halaman Album!</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>