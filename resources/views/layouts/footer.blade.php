<footer class="footer section">
  <div class="container-fluid footer-dark px-5">
    <div class="footer-btm py-3">
      <div class="copyright text-center">
        @if (!empty($moreConfigs['footer_text']))
          <p>{{ $moreConfigs['footer_text'] }}</p>
        @endif
        @if (!empty($moreConfigs['footer_copyright']))
          <p>© {{ $moreConfigs['footer_copyright'] }}</p>
        @endif
      </div>
    </div>
  </div>
</footer>