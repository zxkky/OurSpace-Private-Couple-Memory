<!DOCTYPE html>
<html lang="id">
<head><?php renderPartial('head', ['title' => 'Login - OurSpace']); ?></head>
<body class="min-h-screen flex items-center justify-center">
  <div class="w-full max-w-sm mx-4 bg-[#1f1219] rounded-2xl shadow-lg p-6 sm:p-8 border border-rose-900/40">
    <div class="text-center mb-6">
      <div class="text-4xl mb-2">💕</div>
      <h1 class="font-serif text-3xl font-bold text-rose-50 tracking-wide">OurSpace</h1>
      <p class="text-sm text-rose-300/90">Rumah digital kenangan kalian berdua</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="bg-red-950/40 text-red-400 text-sm rounded-lg px-3 py-2 mb-4"><?= h($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="/login" class="space-y-4">
      <div>
        <label class="text-sm text-rose-200">Username</label>
        <input type="text" name="username" required
          class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/60">
      </div>
      <div>
        <label class="text-sm text-rose-200">Password</label>
        <input type="password" name="password" required
          class="w-full mt-1 px-3 py-2 border border-rose-900/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-500/60">
      </div>
      <button type="submit"
        class="w-full bg-rose-500 hover:bg-rose-600 text-white font-medium py-2 rounded-lg transition">
        Masuk
      </button>
    </form>

    <p class="text-center text-sm text-rose-300/90 mt-4">
      Belum punya akun? <a href="/register" class="text-rose-400 font-medium">Daftar</a>
    </p>
  </div>
</body>
</html>