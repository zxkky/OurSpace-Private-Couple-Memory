<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Timeline - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-2xl mx-auto px-4 pt-6 pb-24 md:pb-8">
    <h1 class="text-xl font-bold text-rose-50 mb-6">📅 Timeline Kenangan</h1>

    <?php if (count($groups) === 0): ?>
      <p class="text-rose-400/60 text-sm">Belum ada kenangan. Yuk upload foto pertama kalian di halaman Album!</p>
    <?php endif; ?>

    <div class="space-y-8">
      <?php foreach ($groups as $label => $items): ?>
        <div>
          <p class="text-sm font-semibold text-rose-400 mb-3 sticky top-14 bg-[#170b12]/90 backdrop-blur py-1"><?= h($label) ?></p>
          <div class="grid grid-cols-3 gap-3">
            <?php foreach ($items as $p): ?>
              <a href="/photos/<?= h((string) $p['id']) ?>" class="block aspect-square rounded-lg overflow-hidden bg-[#241420] relative">
                <?php if ($p['media_type'] === 'video'): ?>
                  <video src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)"></video>
                  <span class="absolute bottom-1 right-1 text-white text-xs bg-black/50 rounded px-1">🎥</span>
                <?php else: ?>
                  <img src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
                <?php endif; ?>
                <?php if (!empty($p['caption'])): ?>
                  <span class="absolute bottom-0 left-0 right-0 bg-black/40 text-white text-[10px] px-1.5 py-1 truncate"><?= h($p['caption']) ?></span>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>