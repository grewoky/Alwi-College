<!-- User Profile Dropdown Component -->
<div class="relative" id="userProfileDropdown">
    <!-- Profile Button with Avatar -->
    <button id="userProfileBtn" class="flex items-center gap-3 px-2 py-1 rounded-lg hover:bg-white/10 transition-colors focus:outline-none">
        <!-- Avatar -->
        <div class="w-9 h-9 bg-gradient-to-br from-blue-300 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
        
        <!-- Name -->
        <span class="hidden sm:inline text-white font-medium text-sm">{{ auth()->user()->name }}</span>
        
        <!-- Chevron Icon -->
        <svg class="w-4 h-4 text-white/60 transition-transform" id="dropdownChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div id="userProfileMenu" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden">
        
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 px-6 py-5">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-indigo-600 font-bold text-2xl">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>
                <div class="flex-1">
                    <p class="text-white font-bold text-base">{{ auth()->user()->name }}</p>
                    <p class="text-white/80 text-xs">
                        @php
                            $roles = auth()->user()->getRoleNames();
                            $roleLabel = 'User';
                            if ($roles->contains('student')) $roleLabel = 'Siswa';
                            elseif ($roles->contains('teacher')) $roleLabel = 'Guru';
                            elseif ($roles->contains('admin')) $roleLabel = 'Administrator';
                        @endphp
                        {{ $roleLabel }}
                    </p>
                </div>
            </div>
        </div>

        <!-- User Info Section -->
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3">Informasi Akun</h3>
            
            <!-- Email -->
            <div class="mb-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Email</p>
                <p class="text-sm text-gray-900 font-medium break-all">{{ auth()->user()->email }}</p>
            </div>

            <!-- Phone -->
            <div class="mb-3">
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Telepon</p>
                <p class="text-sm text-gray-900">{{ auth()->user()->phone ?? '— Tidak diisi —' }}</p>
            </div>

            <!-- Status -->
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mb-2">Status Akun</p>
                <div class="flex gap-2">
                    @if(auth()->user()->is_active)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                            Nonaktif
                        </span>
                    @endif
                    
                    @if(auth()->user()->is_approved)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                            Terverifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                            Menunggu
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="px-6 py-4 space-y-2">
            <!-- Change Password Button -->
            <button id="changePasswordBtnDropdown" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-lg hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all text-gray-700 font-medium group">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <div class="flex-1">
                    <p class="group-hover:text-blue-600">Ubah Password</p>
                    <p class="text-xs text-gray-500">Amankan akun Anda</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Divider -->
            <div class="my-2 border-t border-gray-200"></div>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left rounded-lg hover:bg-red-50 transition-all text-red-600 font-medium group">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <div class="flex-1">
                        <p class="group-hover:text-red-700">Logout</p>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        
        <!-- Modal Header -->
        <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-5 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                Ubah Password
            </h2>
            <button id="closePasswordModal" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="changePasswordForm" class="p-6 space-y-5">
            @csrf

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <span>Password Saat Ini</span>
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" 
                        class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="Masukkan password saat ini">
                    <button type="button" class="togglePassword absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition" 
                        onclick="togglePasswordVisibility(this, 'current_password')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <span id="current_password_error" class="text-red-600 text-sm hidden mt-1 block"></span>
            </div>

            <!-- New Password -->
            <div>
                <label for="new_password" class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <span>Password Baru</span>
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password" 
                        class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="Masukkan password baru">
                    <button type="button" class="togglePassword absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition" 
                        onclick="togglePasswordVisibility(this, 'new_password')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-600 mt-1.5 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Minimal 8 karakter
                </p>
                <span id="new_password_error" class="text-red-600 text-sm hidden mt-1 block"></span>
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="new_password_confirmation" class="block text-sm font-bold text-gray-900 mb-2 flex items-center gap-2">
                    <span>Konfirmasi Password</span>
                    <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" 
                        class="w-full px-4 py-2.5 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="Konfirmasi password baru">
                    <button type="button" class="togglePassword absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 transition" 
                        onclick="togglePasswordVisibility(this, 'new_password_confirmation')">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <span id="new_password_confirmation_error" class="text-red-600 text-sm hidden mt-1 block"></span>
            </div>

            <!-- Alert Messages -->
            <div id="successAlert" class="hidden bg-green-50 border-2 border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-bold">Berhasil!</p>
                    <p id="successMessage" class="text-sm">Password berhasil diubah.</p>
                </div>
            </div>

            <div id="errorAlert" class="hidden bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <div>
                    <p class="font-bold">Terjadi Kesalahan!</p>
                    <p id="errorMessage" class="text-sm">Password saat ini salah.</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button type="button" id="cancelPasswordBtn" class="flex-1 px-4 py-2.5 border-2 border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button type="submit" id="submitPasswordBtn" class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg font-bold hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Profile Dropdown Toggle
    const userProfileBtn = document.getElementById('userProfileBtn');
    const userProfileMenu = document.getElementById('userProfileMenu');
    const userProfileDropdown = document.getElementById('userProfileDropdown');
    const dropdownChevron = document.getElementById('dropdownChevron');

    userProfileBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userProfileMenu.classList.toggle('hidden');
        dropdownChevron.style.transform = userProfileMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    });

    document.addEventListener('click', (e) => {
        if (!userProfileDropdown.contains(e.target)) {
            userProfileMenu.classList.add('hidden');
            dropdownChevron.style.transform = 'rotate(0deg)';
        }
    });

    // Change Password Modal
    const changePasswordBtnDropdown = document.getElementById('changePasswordBtnDropdown');
    const changePasswordModal = document.getElementById('changePasswordModal');
    const closePasswordModal = document.getElementById('closePasswordModal');
    const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');
    const changePasswordForm = document.getElementById('changePasswordForm');
    const submitPasswordBtn = document.getElementById('submitPasswordBtn');

    changePasswordBtnDropdown.addEventListener('click', () => {
        changePasswordModal.classList.remove('hidden');
        userProfileMenu.classList.add('hidden');
        dropdownChevron.style.transform = 'rotate(0deg)';
        resetPasswordForm();
    });

    closePasswordModal.addEventListener('click', () => {
        changePasswordModal.classList.add('hidden');
    });

    cancelPasswordBtn.addEventListener('click', () => {
        changePasswordModal.classList.add('hidden');
    });

    changePasswordModal.addEventListener('click', (e) => {
        if (e.target === changePasswordModal) {
            changePasswordModal.classList.add('hidden');
        }
    });

    // Toggle password visibility
    function togglePasswordVisibility(button, fieldId) {
        const field = document.getElementById(fieldId);
        const isPassword = field.type === 'password';
        field.type = isPassword ? 'text' : 'password';
        
        button.innerHTML = isPassword ? 
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-4.753 4.753m4.753-4.753L9.172 9.172" /></svg>' :
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>';
    }

    // Reset form
    function resetPasswordForm() {
        changePasswordForm.reset();
        document.getElementById('successAlert').classList.add('hidden');
        document.getElementById('errorAlert').classList.add('hidden');
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
    }

    // Handle form submission
    changePasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        document.querySelectorAll('[id$="_error"]').forEach(el => el.classList.add('hidden'));
        document.getElementById('successAlert').classList.add('hidden');
        document.getElementById('errorAlert').classList.add('hidden');

        const currentPassword = document.getElementById('current_password').value;
        const newPassword = document.getElementById('new_password').value;
        const newPasswordConfirmation = document.getElementById('new_password_confirmation').value;

        let hasError = false;

        if (!currentPassword.trim()) {
            document.getElementById('current_password_error').textContent = 'Password saat ini tidak boleh kosong';
            document.getElementById('current_password_error').classList.remove('hidden');
            hasError = true;
        }

        if (!newPassword.trim()) {
            document.getElementById('new_password_error').textContent = 'Password baru tidak boleh kosong';
            document.getElementById('new_password_error').classList.remove('hidden');
            hasError = true;
        } else if (newPassword.length < 8) {
            document.getElementById('new_password_error').textContent = 'Password harus minimal 8 karakter';
            document.getElementById('new_password_error').classList.remove('hidden');
            hasError = true;
        }

        if (newPassword !== newPasswordConfirmation) {
            document.getElementById('new_password_confirmation_error').textContent = 'Konfirmasi password tidak cocok';
            document.getElementById('new_password_confirmation_error').classList.remove('hidden');
            hasError = true;
        }

        if (hasError) return;

        submitPasswordBtn.disabled = true;
        submitPasswordBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> Memproses...';

        try {
            const response = await fetch('{{ route("profile.update-password") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({
                    current_password: currentPassword,
                    new_password: newPassword,
                    new_password_confirmation: newPasswordConfirmation,
                })
            });

            const data = await response.json();

            if (response.ok) {
                document.getElementById('successAlert').classList.remove('hidden');
                changePasswordForm.reset();
                
                setTimeout(() => {
                    changePasswordModal.classList.add('hidden');
                    location.reload();
                }, 2000);
            } else {
                document.getElementById('errorAlert').classList.remove('hidden');
                document.getElementById('errorMessage').textContent = data.message || 'Terjadi kesalahan saat mengubah password';
                
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(field + '_error');
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('errorAlert').classList.remove('hidden');
            document.getElementById('errorMessage').textContent = 'Terjadi kesalahan jaringan. Coba lagi nanti.';
        } finally {
            submitPasswordBtn.disabled = false;
            submitPasswordBtn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg><span>Simpan</span>';
        }
    });
</script>
