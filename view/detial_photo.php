<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Kenangan - OurSpace']); ?></head>
<body class="min-h-screen">
  <?php renderPartial('navbar'); ?>

  <div class="max-w-2xl mx-auto px-4 pt-6 pb-24 md:pb-8">
    <div class="flex items-center justify-between">
      <a href="/albums/<?= h((string) $photo['album_id']) ?>" class="text-sm text-rose-400">← Kembali ke <?= h($photo['album_name']) ?></a>
      <div class="flex gap-2">
        <a href="#edit-photo" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-rose-900/40 text-rose-200 hover:bg-[#1c0f16]">Edit</a>
        <form method="POST" action="/photos/<?= h((string) $photo['id']) ?>/delete"
          onsubmit="return confirm('Hapus foto/video ini beserta semua like dan komentarnya?');">
          <button type="submit" class="text-xs font-medium px-3 py-1.5 rounded-lg border border-red-900/50 text-red-400 hover:bg-red-950/40">Hapus</button>
        </form>
      </div>
    </div>

    <div class="bg-[#1f1219] rounded-xl overflow-hidden border border-rose-900/40 shadow-sm mt-4">
      <div class="bg-black flex items-center justify-center">
        <?php if ($photo['media_type'] === 'video'): ?>
          <video src="/uploads/<?= h($photo['filename']) ?>" controls class="max-h-[70vh] w-full" onerror="oimgErr(this)"></video>
        <?php else: ?>
          <img src="/uploads/<?= h($photo['filename']) ?>" class="max-h-[70vh] w-full object-contain" onerror="oimgErr(this)">
        <?php endif; ?>
      </div>

      <div class="p-5">
        <?php if (!empty($photo['caption'])): ?><p class="text-rose-50 mb-2"><?= h($photo['caption']) ?></p><?php endif; ?>
        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-rose-300/90 mb-4">
          <?php if (!empty($photo['location'])): ?><span>📍 <?= h($photo['location']) ?></span><?php endif; ?>
          <?php if (!empty($photo['taken_at'])): ?><span>📅 <?= h(formatDateId($photo['taken_at'])) ?></span><?php endif; ?>
        </div>

        <form method="POST" action="/photos/<?= h((string) $photo['id']) ?>/like" class="inline">
          <button type="submit" class="text-sm px-3 py-1.5 rounded-full border <?= $photo['liked_by_me'] ? 'bg-rose-500 text-white border-rose-500' : 'border-rose-900/40 text-rose-200' ?>">
            ❤️ <?= h((string) $photo['like_count']) ?>
          </button>
        </form>

        <!-- Form edit foto -->
        <details id="edit-photo" class="mt-4 bg-rose-950/40 rounded-xl border border-rose-900/40">
          <summary class="cursor-pointer px-4 py-2 text-sm font-medium text-rose-400">✏️ Edit Detail Foto</summary>
          <form method="POST" action="/photos/<?= h((string) $photo['id']) ?>/edit" class="px-4 pb-4 space-y-3">
            <input type="text" name="caption" placeholder="Caption" value="<?= h($photo['caption'] ?? '') ?>"
              class="w-full px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <input type="text" name="location" placeholder="Lokasi" value="<?= h($photo['location'] ?? '') ?>"
                class="px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
              <input type="date" name="taken_at" value="<?= h($photo['taken_at'] ? date('Y-m-d', strtotime($photo['taken_at'])) : '') ?>"
                class="px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
            </div>
            <button type="submit" class="w-full sm:w-auto bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
              Simpan
            </button>
          </form>
        </details>

        <div class="mt-6 border-t border-rose-900/30 pt-4">
          <p class="text-sm font-medium text-rose-100 mb-3">💬 Komentar</p>
          <div class="space-y-3 mb-4">
            <?php foreach ($comments as $c): ?>
              <div class="text-sm">
                <div class="flex items-start justify-between gap-2">
                  <p><span class="font-medium text-rose-50"><?= h($c['display_name']) ?>:</span>
                    <span class="text-rose-200"><?= h($c['comment']) ?></span></p>
                  <?php if ($currentUser && $currentUser['id'] === $c['user_id']): ?>
                    <div class="flex gap-2 shrink-0 text-xs">
                      <a href="#edit-comment-<?= h((string) $c['id']) ?>" class="text-rose-400/60 hover:text-rose-400">Edit</a>
                      <form method="POST" action="/comments/<?= h((string) $c['id']) ?>/delete" onsubmit="return confirm('Hapus komentar ini?');">
                        <button type="submit" class="text-rose-400/60 hover:text-red-400">Hapus</button>
                      </form>
                    </div>
                  <?php endif; ?>
                </div>
                <?php if ($currentUser && $currentUser['id'] === $c['user_id']): ?>
                  <details id="edit-comment-<?= h((string) $c['id']) ?>" class="mt-1">
                    <summary class="sr-only">Edit komentar</summary>
                    <form method="POST" action="/comments/<?= h((string) $c['id']) ?>/edit" class="flex gap-2 mt-1">
                      <input type="text" name="comment" value="<?= h($c['comment']) ?>" required
                        class="flex-1 px-3 py-1.5 border border-rose-900/40 rounded-lg text-sm">
                      <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-medium px-3 rounded-lg">Simpan</button>
                    </form>
                  </details>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
            <?php if (count($comments) === 0): ?>
              <p class="text-sm text-rose-400/60">Belum ada komentar.</p>
            <?php endif; ?>
          </div>
          <form method="POST" action="/photos/<?= h((string) $photo['id']) ?>/comment" class="flex gap-2">
            <input type="text" name="comment" placeholder="Tulis komentar..." required
              class="flex-1 px-3 py-2 border border-rose-900/40 rounded-lg text-sm">
            <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-medium px-4 rounded-lg">
              Kirim
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>