<?php
$__path = $currentPath ?? '';
$isActive = function (string $prefix) use ($__path): bool {
    return $__path === $prefix || str_starts_with($__path, $prefix . '/');
};
?>
<nav class="bg-[#170b12]/80 backdrop-blur border-b border-rose-900/40 sticky top-0 z-20">
  <div class="max-w-5xl mx-auto px-4 flex items-center justify-between h-14">
    <a href="/dashboard" class="font-serif font-bold text-rose-400 text-xl shrink-0 tracking-wide">💕 OurSpace</a>

    <!-- Desktop nav links -->
    <div class="hidden md:flex items-center gap-4 text-sm text-rose-200">
      <a href="/dashboard" class="hover:text-rose-400 <?= $isActive('/dashboard') ? 'text-rose-400 font-medium' : '' ?>">Dashboard</a>
      <a href="/albums" class="hover:text-rose-400 <?= ($isActive('/albums') || $isActive('/photos')) ? 'text-rose-400 font-medium' : '' ?>">Album</a>
      <a href="/timeline" class="hover:text-rose-400 <?= $isActive('/timeline') ? 'text-rose-400 font-medium' : '' ?>">Timeline</a>
      <a href="/calendar" class="hover:text-rose-400 <?= $isActive('/calendar') ? 'text-rose-400 font-medium' : '' ?>">Kalender</a>
      <?php if ($currentUser): ?>
        <span class="text-rose-400/60">|</span>
        <span class="text-rose-300/90"><?= h($currentUser['display_name']) ?></span>
        <a href="/logout" class="hover:text-rose-400">Keluar</a>
      <?php endif; ?>
    </div>

    <!-- Mobile: nama + keluar saja, navigasi utama ada di tab bar bawah -->
    <?php if ($currentUser): ?>
      <div class="flex md:hidden items-center gap-3 text-xs">
        <span class="text-rose-300/90 truncate max-w-[90px]"><?= h($currentUser['display_name']) ?></span>
        <a href="/logout" class="text-rose-400 font-medium">Keluar</a>
      </div>
    <?php endif; ?>
  </div>
</nav>

<!-- Mobile bottom tab bar -->
<?php if ($currentUser): ?>
  <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-[#170b12]/95 backdrop-blur border-t border-rose-900/40 z-20"
       style="padding-bottom: env(safe-area-inset-bottom);">
    <div class="grid grid-cols-4 h-16">
      <a href="/dashboard" class="flex flex-col items-center justify-center gap-0.5 text-[11px] <?= $isActive('/dashboard') ? 'text-rose-400 font-semibold' : 'text-rose-300/90' ?>">
        <span class="text-lg leading-none">🏠</span>Dashboard
      </a>
      <a href="/albums" class="flex flex-col items-center justify-center gap-0.5 text-[11px] <?= ($isActive('/albums') || $isActive('/photos')) ? 'text-rose-400 font-semibold' : 'text-rose-300/90' ?>">
        <span class="text-lg leading-none">📁</span>Album
      </a>
      <a href="/timeline" class="flex flex-col items-center justify-center gap-0.5 text-[11px] <?= $isActive('/timeline') ? 'text-rose-400 font-semibold' : 'text-rose-300/90' ?>">
        <span class="text-lg leading-none">📅</span>Timeline
      </a>
      <a href="/calendar" class="flex flex-col items-center justify-center gap-0.5 text-[11px] <?= $isActive('/calendar') ? 'text-rose-400 font-semibold' : 'text-rose-300/90' ?>">
        <span class="text-lg leading-none">🗓️</span>Kalender
      </a>
    </div>
  </nav>
<?php endif; ?>