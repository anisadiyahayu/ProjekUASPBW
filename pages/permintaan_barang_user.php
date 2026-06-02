<html>

<head>
    <style id="reset-css">
        @layer figreset, figoverridable, reset, theme, base, figutils, components, utilities;

        @layer figoverridable {
            :root {
                font-synthesis: none
            }
        }

        @layer figutils {
            :root {
                --banner-height: 48px;
                --banner-height-v2: 40px;
                --full-height-with-banner: calc(100dvh - var(--banner-height))
            }

            @media (max-width:600px) {
                .banner-v2-container {
                    left: 0 !important;
                    right: 0 !important;
                    margin: 0 auto !important
                }
            }

            .wrapper-with-banner .min-h-screen {
                min-height: var(--full-height-with-banner)
            }

            .wrapper-with-banner .h-screen {
                height: var(--full-height-with-banner)
            }
        }
    </style>
    <meta name="color-scheme" content="light dark">
    <style>
        html,
        body,
        #container,
        #container>div {
            height: 100%;
        }
    </style>
    <link rel="stylesheet" href="https://s3-figma-foundry-cached-previews-production-sig.figma.com/a8104c13-6732-44e5-8978-634b29138b54/index.css?Expires=1781481600&amp;Key-Pair-Id=APKAQ4GOSFWCW27IBOMQ&amp;Signature=XBFglz2jfT7JuVexyy2suSySzZocFPNAvcBGiVWV~M96MM1dOS2gxJWQMK5tIHvz6ukdxlLpdNOJBRVGETRmoitRiviY9T~KNL2YmuqzercJA7BCQGuSSp-EEfW26DvSqxPrhiLqK5ENk0vQPPNK2cfCI0RM~w3BjXn0F-hB7uRjOh-fM-gRRfZ2Zc6E8Eaj8o25qXmS6IpQUWfiZVOGhC2~A9BDHmm4nAgj8qKkNEVFTR4lhSFUFlqoJ9NLDLFoGsbysQQ76M9ypK6A6Ea~zJr2qwwJDEp3hnYkiq~uUBKNVPB38NV0Y19~lkHiLZuUdPq3Bat3bSqB3zV1pZ2XcA__">
</head>

<body>
    <script>
        window.coreMessagePort = null
        window.messagePort = null

        const allowedOrigins = [
            'https://figma-gov.com',
            'https://www.figma.com',
            'https://staging.figma.com',
            'https://devenv01.figma.engineering',
            'https://local.figma.engineering:8443',
            'http://localhost:9000',
        ]

        const allowedOriginPatterns = [
            /^https:\/\/[a-z0-9-]+\.figdev\.systems:8443$/,
            /^https:\/\/[a-z0-9-]+\.figdev\.systems$/,
        ]

        function isAllowedOrigin(origin) {
            return allowedOrigins.includes(origin) || allowedOriginPatterns.some(p => p.test(origin))
        }

        let messageId = 0

        window.addEventListener('message', (e) => {
            function sendMessage(method, data) {
                if (window.coreMessagePort) {
                    window.coreMessagePort.postMessage({
                        method: 'status',
                        args: data,
                        messageId: 0
                    })
                    messageId++
                }
            }

            if (isAllowedOrigin(e.origin)) {
                if (e.data.type === 'iframe-init') {
                    window.coreMessagePort = e.ports[0]
                    window.messagePort = e.ports[1]

                    window.__PREVIEW_IFRAME_INITIAL_OPTIONS__ = e.data.previewIframeInitialOptions

                    sendMessage('status', {
                        state: 'init-received',
                        isReady: false
                    })

                    if (e.data.initScriptBlob) {
                        import(URL.createObjectURL(e.data.initScriptBlob))
                    } else {
                        const script = document.createElement('script')

                        script.onload = async () => {
                            function sendReady() {
                                sendMessage('status', {
                                    state: 'ready',
                                    isReady: true
                                })
                            }

                            if (window.__iframeScriptExecuted__) {
                                sendReady()
                                return
                            }

                            let executeInterval = null
                            let timeout = null

                            const timeoutPromise = new Promise((resolve) => {
                                timeout = setTimeout(() => resolve('timeout'), 2000)
                            })

                            const scriptExecutedPromise = new Promise((resolve) => {
                                executeInterval = setInterval(() => {
                                    if (window.__iframeScriptExecuted__) {
                                        resolve('ready')
                                    }
                                }, 50)
                            })

                            const result = await Promise.race([timeoutPromise, scriptExecutedPromise])

                            clearTimeout(timeout)
                            clearInterval(executeInterval)

                            if (result === 'ready') {
                                sendReady()
                            } else {
                                sendMessage('status', {
                                    state: 'script-timeout',
                                    isReady: false
                                })
                            }
                        }

                        script.onerror = (e) => {
                            sendMessage('status', {
                                state: 'script-load-error',
                                isReady: false,
                                error: e.message
                            })
                        }

                        script.src = e.data.initScriptURL
                        // https://sentry.io/answers/script-error/
                        script.crossOrigin = 'anonymous'
                        document.body.appendChild(script)
                    }
                }
            }
        })
    </script>



    <script src="https://www.figma.com/webpack-artifacts/assets/code_components_preview_iframe-6fc448adff96db0a.min.js.br" crossorigin="anonymous"></script>
    <div id="container">
        <div class="tailwind">
            <div id="fig-code-root" style="height: 100%;">
                <div class="min-h-screen bg-background">
                    <aside class="fixed top-0 left-0 h-full bg-sidebar text-sidebar-foreground transition-all duration-300 z-40 w-64">
                        <div class="flex flex-col h-full">
                            <div class="p-6 border-b border-sidebar-border">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-sidebar-primary flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-6 h-6 text-sidebar-primary-foreground">
                                            <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                            <path d="M12 22V12"></path>
                                            <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                            <path d="m7.5 4.27 9 5.15"></path>
                                        </svg></div>
                                    <div>
                                        <h1 class="font-semibold text-lg">Lab Inventory</h1>
                                        <p class="text-xs text-sidebar-foreground/70">Student Portal</p>
                                    </div>
                                </div>
                            </div>
                            <nav class="flex-1 py-6 overflow-y-auto">
                                <ul class="space-y-1 px-3">
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="/student/dashboard" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-layout-dashboard w-5 h-5">
                                                <rect width="7" height="9" x="3" y="3" rx="1"></rect>
                                                <rect width="7" height="5" x="14" y="3" rx="1"></rect>
                                                <rect width="7" height="9" x="14" y="12" rx="1"></rect>
                                                <rect width="7" height="5" x="3" y="16" rx="1"></rect>
                                            </svg><span class="font-medium">Dashboard</span></a></li>
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="/student/katalog" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-5 h-5">
                                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                                <path d="M12 22V12"></path>
                                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                                <path d="m7.5 4.27 9 5.15"></path>
                                            </svg><span class="font-medium">Katalog Barang</span></a></li>
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all bg-sidebar-primary text-sidebar-primary-foreground shadow-lg" href="/student/peminjaman" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-open w-5 h-5">
                                                <path d="M12 22v-9"></path>
                                                <path d="M15.17 2.21a1.67 1.67 0 0 1 1.63 0L21 4.57a1.93 1.93 0 0 1 0 3.36L8.82 14.79a1.655 1.655 0 0 1-1.64 0L3 12.43a1.93 1.93 0 0 1 0-3.36z"></path>
                                                <path d="M20 13v3.87a2.06 2.06 0 0 1-1.11 1.83l-6 3.08a1.93 1.93 0 0 1-1.78 0l-6-3.08A2.06 2.06 0 0 1 4 16.87V13"></path>
                                                <path d="M21 12.43a1.93 1.93 0 0 0 0-3.36L8.83 2.2a1.64 1.64 0 0 0-1.63 0L3 4.57a1.93 1.93 0 0 0 0 3.36l12.18 6.86a1.636 1.636 0 0 0 1.63 0z"></path>
                                            </svg><span class="font-medium">Peminjaman Barang</span></a></li>
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="/student/pengembalian" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-rotate-ccw w-5 h-5">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                <path d="M3 3v5h5"></path>
                                            </svg><span class="font-medium">Pengembalian Barang</span></a></li>
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="/student/riwayat" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-history w-5 h-5">
                                                <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                                                <path d="M3 3v5h5"></path>
                                                <path d="M12 7v5l4 2"></path>
                                            </svg><span class="font-medium">Riwayat Peminjaman</span></a></li>
                                    <li><a class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all text-sidebar-foreground/80 hover:bg-sidebar-accent hover:text-sidebar-accent-foreground" href="/student/profil" data-discover="true"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user w-5 h-5">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <circle cx="12" cy="10" r="3"></circle>
                                                <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                                            </svg><span class="font-medium">Profil Saya</span></a></li>
                                </ul>
                            </nav>
                            <div class="p-4 border-t border-sidebar-border"><button class="flex items-center gap-3 px-4 py-3 w-full rounded-lg text-sidebar-foreground/80 hover:bg-destructive/20 hover:text-destructive transition-all"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-log-out w-5 h-5">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" x2="9" y1="12" y2="12"></line>
                                    </svg><span class="font-medium">Logout</span></button></div>
                        </div>
                    </aside>
                    <div class="transition-all duration-300 ml-64">
                        <header class="bg-card border-b border-border sticky top-0 z-30 shadow-sm">
                            <div class="flex items-center justify-between px-6 py-4">
                                <div class="flex items-center gap-4"><button class="p-2 hover:bg-muted rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg></button>
                                    <div class="relative w-96"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground">
                                            <circle cx="11" cy="11" r="8"></circle>
                                            <path d="m21 21-4.3-4.3"></path>
                                        </svg><input type="text" placeholder="Cari barang laboratorium..." class="w-full pl-10 pr-4 py-2 bg-input-background rounded-lg border border-border focus:outline-none focus:ring-2 focus:ring-ring/20 transition-all"></div>
                                </div>
                                <div class="flex items-center gap-4"><button class="relative p-2 hover:bg-muted rounded-lg transition-colors"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bell w-5 h-5">
                                            <path d="M10.268 21a2 2 0 0 0 3.464 0"></path>
                                            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"></path>
                                        </svg><span class="absolute -top-1 -right-1 w-5 h-5 bg-destructive text-destructive-foreground text-xs rounded-full flex items-center justify-center font-semibold">2</span></button>
                                    <div class="flex items-center gap-3 pl-4 border-l border-border">
                                        <div class="text-right">
                                            <p class="font-semibold text-sm">Anisa Diyah Ayu Lestari</p>
                                            <p class="text-xs text-muted-foreground">2110631170001 • TI-3A</p>
                                        </div>
                                        <div class="w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center font-semibold">AD</div>
                                    </div>
                                </div>
                            </div>
                        </header>
                        <main class="p-6">
                            <div class="space-y-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h1 class="text-2xl font-semibold text-foreground">Permintaan Barang</h1>
                                        <p class="text-muted-foreground">Kelola permintaan peminjaman barang laboratorium</p>
                                    </div><button class="flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors shadow-md"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-5 h-5">
                                            <path d="M5 12h14"></path>
                                            <path d="M12 5v14"></path>
                                        </svg>Ajukan Permintaan</button>
                                </div>
                                <div class="bg-card rounded-xl shadow-md border border-border p-2">
                                    <div class="flex gap-2"><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors bg-primary text-primary-foreground">Semua Permintaan</button><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors text-muted-foreground hover:bg-muted">Pending</button><button class="flex-1 px-4 py-2 rounded-lg font-medium transition-colors text-muted-foreground hover:bg-muted">Disetujui</button></div>
                                </div>
                                <div class="bg-card rounded-xl shadow-md border border-border overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="w-full">
                                            <thead class="bg-muted/50 border-b border-border">
                                                <tr>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Peminjam</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Barang</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Jumlah</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Keperluan</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Tanggal Pinjam</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Estimasi Kembali</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Status</th>
                                                    <th class="px-6 py-4 text-left text-sm font-semibold text-foreground">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-border">
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Anisa Diyah Ayu Lestari</p>
                                                            <p class="text-sm text-muted-foreground">2110631170001</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">Arduino Uno R3</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">2</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Tugas Akhir</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-28</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-06-10</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-3 h-3">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg> Menunggu</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button><button class="px-3 py-1 text-sm bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition-colors">Validasi</button></div>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Halvina Farras Savitri</p>
                                                            <p class="text-sm text-muted-foreground">2110631170002</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">RFID RC522</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">3</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Penelitian</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-27</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-06-05</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-big w-3 h-3">
                                                                <path d="M21.801 10A10 10 0 1 1 17 3.335"></path>
                                                                <path d="m9 11 3 3L22 4"></path>
                                                            </svg> Disetujui</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button></div>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Rifqy Kurniawan Fattahillah</p>
                                                            <p class="text-sm text-muted-foreground">2110631170003</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">Mikrotik RB750Gr3</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">1</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Praktikum Jaringan</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-29</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-29</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock w-3 h-3">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <polyline points="12 6 12 12 16 14"></polyline>
                                                            </svg> Menunggu</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button><button class="px-3 py-1 text-sm bg-green-50 text-green-600 hover:bg-green-100 rounded-lg transition-colors">Validasi</button></div>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Hazel Muhammad Naufal Ribawa</p>
                                                            <p class="text-sm text-muted-foreground">2110631170004</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">ESP32 DevKit</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">2</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Praktikum IoT</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-26</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-26</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Selesai</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button></div>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Regina Inryanti Simanjuntak</p>
                                                            <p class="text-sm text-muted-foreground">2110631170005</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">Turbidity Sensor</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">1</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Tugas Akhir</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-25</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-06-15</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-x w-3 h-3">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <path d="m15 9-6 6"></path>
                                                                <path d="m9 9 6 6"></path>
                                                            </svg> Ditolak</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button></div>
                                                    </td>
                                                </tr>
                                                <tr class="hover:bg-muted/30 transition-colors">
                                                    <td class="px-6 py-4">
                                                        <div>
                                                            <p class="font-medium text-foreground">Anisa Diyah Ayu Lestari</p>
                                                            <p class="text-sm text-muted-foreground">2110631170001</p>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-foreground">TP-Link TL-WR840N</td>
                                                    <td class="px-6 py-4 text-sm font-semibold text-foreground">2</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">Praktikum Jaringan</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-27</td>
                                                    <td class="px-6 py-4 text-sm text-muted-foreground">2024-05-28</td>
                                                    <td class="px-6 py-4"><span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package w-3 h-3">
                                                                <path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"></path>
                                                                <path d="M12 22V12"></path>
                                                                <polyline points="3.29 7 12 12 20.71 7"></polyline>
                                                                <path d="m7.5 4.27 9 5.15"></path>
                                                            </svg> Dipinjam</span></td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex items-center gap-2"><button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye w-4 h-4">
                                                                    <path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg></button></div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>