@extends('dashboard.layouts.main')
@section('title', 'Social Content Management System')

@section('container')
    <div class="pagetitle">
        <h1>Mixpost Social Content Management System</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item">Social</li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="sections">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-xxl-4 col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Open Mixpost</h5>

                        <p class="card-text text-center"><a class="btn btn-primary"
                          href="#mixpostModal" data-bs-toggle="modal" onclick="openMixpostModal()">Open</a></p>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include Modal -->
    @include('dashboard.partials.social-modal')

    <script>
      function openMixpostModal() {
          document.getElementById('mixpostIframe').src = '/mixpost'; // Your Mixpost URL
      }
    </script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
          const mixpostIframe = document.getElementById('mixpostIframe');
          
          mixpostIframe.addEventListener('load', function() {
              const iframeDoc = mixpostIframe.contentDocument || mixpostIframe.contentWindow.document;
      
              if (iframeDoc) {
                  // Change Facebook login links to open in a new tab
                  const fbLoginLinks = iframeDoc.querySelectorAll('a[href*="facebook.com"]');
                  fbLoginLinks.forEach(link => {
                      link.setAttribute('target', '_blank');
                  });
      
                  // Add similar logic for other OAuth providers if needed
              }
          });
      });
    </script>
      
@endsection
