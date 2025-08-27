<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'Soalan Lazim (FAQ) - E-Kubur' }}</title>
    
    <!-- Favicon -->
    <x-favicon />
    
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col" data-theme="corporate" x-data="faqAccordion()">
    <x-double-navbar :user="$user" />
    
    <main class="flex-1">
        <div class="container mx-auto px-0 py-0">
            <!-- Main Container -->
            <div class="bg-white shadow-lg border-x border-gray-200 p-6">
                <!-- Header Section -->
                <div class="mb-6 text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900 mb-2">Soalan Lazim (FAQ)</h1>
                    <p class="text-xs text-gray-600">Jawapan kepada soalan-soalan yang sering ditanya tentang Sistem E-Kubur</p>
                </div>

                <!-- Search Section -->
                <div class="mb-6">
                    <div class="relative max-w-md mx-auto sm:mx-0">
                        <span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">search</span>
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Cari soalan atau jawapan..." 
                               class="w-full pl-9 pr-3 py-2 border border-gray-200 rounded-xs text-xs focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-500 text-gray-900">
                    </div>
                    <!-- Search Results Counter -->
                    <div x-show="searchQuery" class="mt-2 text-center sm:text-left">
                        <p class="text-xs text-gray-500" x-text="getSearchResultsCount() + ' soalan dijumpai'"></p>
                    </div>
                </div>

                <!-- FAQ Categories -->
                <div class="space-y-6">
                    @foreach($faqs as $categoryIndex => $category)
                    <div class="bg-gray-50 rounded-xs border border-gray-200 p-6">
                        <!-- Category Header -->
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full 
                                @if($category['color'] === 'blue') bg-blue-100 text-blue-600
                                @elseif($category['color'] === 'green') bg-green-100 text-green-600
                                @elseif($category['color'] === 'purple') bg-purple-100 text-purple-600
                                @elseif($category['color'] === 'orange') bg-orange-100 text-orange-600
                                @elseif($category['color'] === 'red') bg-red-100 text-red-600
                                @endif
                                flex items-center justify-center mr-3">
                                <span class="material-icons text-lg">{{ $category['icon'] }}</span>
                            </div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $category['category'] }}</h2>
                        </div>

                        <!-- FAQ Items -->
                        <div class="space-y-3">
                            @foreach($category['questions'] as $questionIndex => $faq)
                            <div class="bg-white rounded-xs border border-gray-200 overflow-hidden"
                                 x-show="filterFAQ('{{ $faq['question'] }}', '{{ $faq['answer'] }}')">
                                <!-- Question Header -->
                                <button @click="toggleFAQ({{ $categoryIndex }}, {{ $questionIndex }})" 
                                        class="w-full px-4 py-3 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                                    <span class="text-sm font-medium text-gray-900 pr-4">{{ $faq['question'] }}</span>
                                    <span class="material-icons text-gray-500 transition-transform duration-200"
                                          :class="{ 'rotate-180': openFAQ === '{{ $categoryIndex }}-{{ $questionIndex }}' }">
                                        expand_more
                                    </span>
                                </button>
                                
                                <!-- Answer Content -->
                                <div x-show="openFAQ === '{{ $categoryIndex }}-{{ $questionIndex }}'"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                                     x-transition:enter-end="opacity-100 transform translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 transform translate-y-0"
                                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                                     class="border-t border-gray-200">
                                    <div class="px-4 py-3">
                                        <p class="text-xs text-gray-700 leading-relaxed">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- No Results Message -->
                <div x-show="!hasResults()" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="text-center py-8">
                    <div class="text-gray-500">
                        <span class="material-icons text-4xl mb-2 block">search_off</span>
                        <p class="text-sm">Tiada soalan yang sepadan dengan carian anda.</p>
                        <p class="text-xs mt-1">Cuba kata kunci yang berbeza atau lihat semua soalan di atas.</p>
                    </div>
                </div>

                <!-- Contact Support Section -->
                <div class="mt-8 bg-blue-50 rounded-xs border border-blue-200 p-6">
                    <div class="text-center">
                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto mb-3">
                            <span class="material-icons text-xl">support_agent</span>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-2">Masih tidak jumpa jawapan?</h3>
                        <p class="text-xs text-gray-600 mb-4">Jika anda masih mempunyai soalan, sila hubungi pasukan sokongan kami.</p>
                        <div class="flex flex-col sm:flex-row justify-center space-y-3 sm:space-y-0 sm:space-x-3">
                            <button class="px-4 py-3 bg-blue-600 text-white text-xs rounded-xs hover:bg-blue-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">email</span>
                                Hantar Emel
                            </button>
                            <button class="px-4 py-3 bg-green-600 text-white text-xs rounded-xs hover:bg-green-700 transition-colors flex items-center justify-center">
                                <span class="material-icons text-xs mr-2">phone</span>
                                Hubungi Kami
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-footer />

    <script>
        function faqAccordion() {
            return {
                openFAQ: null,
                searchQuery: '',
                
                toggleFAQ(categoryIndex, questionIndex) {
                    const faqKey = `${categoryIndex}-${questionIndex}`;
                    if (this.openFAQ === faqKey) {
                        this.openFAQ = null;
                    } else {
                        this.openFAQ = faqKey;
                    }
                },
                
                filterFAQ(question, answer) {
                    if (!this.searchQuery) return true;
                    
                    const query = this.searchQuery.toLowerCase();
                    return question.toLowerCase().includes(query) || 
                           answer.toLowerCase().includes(query);
                },
                
                hasResults() {
                    if (!this.searchQuery) return true;
                    
                    const query = this.searchQuery.toLowerCase();
                    return this.$el.querySelectorAll('[x-show*="filterFAQ"]').length > 0;
                },
                
                getSearchResultsCount() {
                    if (!this.searchQuery) return 0;
                    
                    let count = 0;
                    this.$el.querySelectorAll('[x-show*="filterFAQ"]').forEach(element => {
                        if (element.style.display !== 'none') {
                            count++;
                        }
                    });
                    return count;
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar for FAQ answers */
        .faq-answer {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 #F7FAFC;
        }
        
        .faq-answer::-webkit-scrollbar {
            width: 6px;
        }
        
        .faq-answer::-webkit-scrollbar-track {
            background: #F7FAFC;
        }
        
        .faq-answer::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 3px;
        }
        
        .faq-answer::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }
    </style>
</body>
</html>
