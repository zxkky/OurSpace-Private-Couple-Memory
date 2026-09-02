<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#170b12">
<title><?= h($title ?? 'OurSpace') ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          rose: {
            50: '#fdf1f4', 100: '#f7dbe3', 300: '#e98aa3', 400: '#ef5d80',
            500: '#e11d48', 600: '#be123c', 700: '#9f1239', 800: '#6b1029',
            900: '#3f0a1c', 950: '#26050f'
          }
        },
        fontFamily: {
          sans: ['Inter', 'ui-sans-serif', 'system-ui'],
          serif: ['"Cormorant Garamond"', 'ui-serif', 'Georgia', 'serif']
        }
      }
    }
  }
</script>
<style>
  body {
    background:
      radial-gradient(1200px 600px at 50% -10%, rgba(225, 29, 72, 0.16), transparent 60%),
      linear-gradient(180deg, #170b12 0%, #100810 400px, #0c060c 100%);
    background-attachment: fixed;
    color: #f7dbe3;
    -webkit-tap-highlight-color: transparent;
  }
  input, select, textarea, button { font-size: 16px; } /* cegah auto-zoom Safari iOS saat fokus input */
  input, select, textarea {
    background-color: rgba(255, 255, 255, 0.03);
    color: #fdf1f4;
  }
  input::placeholder, textarea::placeholder { color: rgba(247, 219, 227, 0.35); }
  ::-webkit-scrollbar { display: none; }
  ::selection { background: #9f1239; color: #fdf1f4; }
</style>
<script>
  // Dipanggil lewat onerror="oimgErr(this)" pada <img>/<video> yang sumbernya
  // dari /uploads/... . Kalau filenya tidak ditemukan di server (404, dihapus
  // manual, dsb), elemen diganti kotak placeholder ketimbang ikon broken-image.
  function oimgErr(el) {
    el.onerror = null;
    var isVideo = el.tagName === 'VIDEO';
    var box = document.createElement('div');
    box.className = 'w-full h-full min-h-[120px] flex flex-col items-center ' +
      'justify-center gap-1 bg-[#241420] text-rose-400/50 rounded-[inherit]';
    box.innerHTML = '<span class="text-2xl leading-none">' + (isVideo ? '🎬' : '🖼️') + '</span>' +
      '<span class="text-[10px] leading-tight text-center px-2">File tidak ditemukan</span>';
    el.replaceWith(box);
  }
</script>
