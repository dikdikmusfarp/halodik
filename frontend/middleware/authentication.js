export default function ({ $axios, store, redirect }) {
  if (!store.state.auth.token) {
  // Jika pengguna belum login, redirect ke halaman login
    return redirect('/login')
  }
  else {
  // Menambahkan token ke header permintaan jika tersedia

    $axios.setHeader('Authorization', `Bearer ${store.state.auth.token}`);
  }
}
