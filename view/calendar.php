<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Kalender - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-2xl mx-auto px-4 pt-6 pb-24 md:pb-8">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-xl font-bold text-rose-50">🗓️ Kalender Kenangan</h1>
      <form method="GET" action="/calendar" class="flex items-center gap-2">
        <select name="month" class="text-sm border border-rose-900/40 rounded-lg px-2 py-1.5" onchange="this.form.submit()">
          <?php foreach ($monthNames as $num => $nm): ?>
            <option value="<?= h((string) $num) ?>" <?= $num === $month ? 'selected' : '' ?>><?= h($nm) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="year" class="text-sm border border-rose-900/40 rounded-lg px-2 py-1.5" onchange="this.form.submit()">
          <?php foreach ($yearOptions as $y): ?>
            <option value="<?= h((string) $y) ?>" <?= $y === $year ? 'selected' : '' ?>><?= h((string) $y) ?></option>
          <?php endforeach; ?>
        </select>
      </form>
    </div>

    <div class="bg-[#1f1219] rounded-xl border border-rose-900/40 shadow-sm p-4">
      <div class="grid grid-cols-7 gap-1 text-center text-[11px] text-rose-400/60 mb-2">
        <?php foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $d): ?>
          <span><?= h($d) ?></span>
        <?php endforeach; ?>
      </div>
      <div class="grid grid-cols-7 gap-1">
        <?php for ($i = 0; $i < $startOffset; $i++): ?>
          <div></div>
        <?php endfor; ?>
        <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
          <?php $dayPhotos = $photosByDay[$day] ?? []; ?>
          <?php if (count($dayPhotos) > 0): ?>
            <a href="#day-<?= $day ?>" class="aspect-square rounded-lg overflow-hidden relative bg-rose-950/40 border border-rose-800/60">
              <img src="/uploads/<?= h($dayPhotos[0]['filename']) ?>" class="w-full h-full object-cover opacity-90" onerror="oimgErr(this)">
              <span class="absolute bottom-0.5 right-0.5 text-[10px] text-white bg-black/50 rounded px-1"><?= h((string) $day) ?></span>
            </a>
          <?php else: ?>
            <div class="aspect-square rounded-lg flex items-center justify-center text-xs text-rose-500/40"><?= h((string) $day) ?></div>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    </div>

    <div class="mt-8 space-y-6">
      <?php foreach ($photosByDay as $day => $items): ?>
        <div id="day-<?= $day ?>">
          <p class="text-sm font-semibold text-rose-100 mb-3"><?= h((string) $day) ?> <?= h($monthNames[$month]) ?> <?= h((string) $year) ?></p>
          <div class="grid grid-cols-3 gap-3">
            <?php foreach ($items as $p): ?>
              <a href="/photos/<?= h((string) $p['id']) ?>" class="block aspect-square rounded-lg overflow-hidden bg-[#241420]">
                <?php if ($p['media_type'] === 'video'): ?>
                  <video src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)"></video>
                <?php else: ?>
                  <img src="/uploads/<?= h($p['filename']) ?>" class="w-full h-full object-cover" onerror="oimgErr(this)">
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (count($photosByDay) === 0): ?>
        <p class="text-rose-400/60 text-sm text-center">Tidak ada kenangan di bulan ini.</p>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>