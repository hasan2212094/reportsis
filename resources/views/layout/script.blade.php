 <!-- Core JS -->
 <!-- build:js assets/vendor/js/core.js -->
 <script src="{{ asset('sneat') }}/assets/vendor/libs/jquery/jquery.js"></script>
 <script src="{{ asset('sneat') }}/assets/vendor/libs/popper/popper.js"></script>
 <script src="{{ asset('sneat') }}/assets/vendor/js/bootstrap.js"></script>
 <script src="{{ asset('sneat') }}/assets/vendor/main/main.js"></script>
 <script src="{{ asset('sneat') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

 <script src="{{ asset('sneat') }}/assets/vendor/js/menu.js"></script>
 <!-- endbuild -->

 <!-- Vendors JS -->
 <script src="{{ asset('sneat') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>

 <!-- Main JS -->
 <script src="{{ asset('sneat') }}/assets/js/main.js"></script>

 <!-- Page JS -->
 <script src="{{ asset('sneat') }}/assets/js/dashboards-analytics.js"></script>

 <!-- Select2 -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

 @stack('scripts')


 <!-- Place this tag in your head or just before your close body tag. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
 <script>
     new DataTable('#example');
 </script>
