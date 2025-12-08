<nav id="sidebar"
     class="hidden md:flex md:flex-col md:w-60 bg-slate-900 text-slate-100 p-4 space-y-4">
    <div class="flex items-center gap-2 mb-4">
        <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-brand">
            SP
        </span>
        <span class="font-semibold tracking-tight">Sales Pro</span>
    </div>

    <hr class="border-slate-700">

    <ul class="flex flex-col gap-1 text-sm">
        <li>
            <a href="?page=dashboard"
               class="flex items-center gap-2 rounded-lg px-3 py-2
                      <?= ($activeMenu === 'dashboard'
                          ? 'bg-slate-800 text-white'
                          : 'text-slate-200 hover:bg-slate-800') ?>">
                <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="?page=orders"
               class="rounded-lg px-3 py-2 text-slate-200 hover:bg-slate-800">
                Orders
            </a>
        </li>
        <li>
            <a href="?page=reports"
               class="rounded-lg px-3 py-2 text-slate-200 hover:bg-slate-800">
                Reports
            </a>
        </li>
    </ul>
</nav>

<main id="content" class="flex-1 flex flex-col">
    <header class="bg-white border-b border-slate-200">
        <div class="px-4 lg:px-6 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-semibold"><?= htmlspecialchars($pageTitle ?? '') ?></h1>
                <p class="text-xs text-slate-500">Sales overview</p>
            </div>
            <div class="text-right text-xs text-slate-500">
                <?= date('l d F, Y') ?><br>
                <span class="font-medium">JDE: 25342</span>
            </div>
        </div>
    </header>

    <section class="px-4 lg:px-6 py-4 space-y-4">
