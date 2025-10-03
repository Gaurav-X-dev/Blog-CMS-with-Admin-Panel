  <!-- Drawer -->
  <div class="drawer" id="drawer">
      <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0 fw-bold">QT</h4>
          <button class="btn btn-dark rounded-circle" onclick="toggleDrawer()"
              style="width: 36px; height: 36px; padding: 0;">×</button>
      </div>
      <!-- Menu Items -->
      <div class="drawer-menu">
          <!-- About Us (Dropdown) -->
          <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="drawer-item border-bottom py-2">
                  <a href="<?php echo e(route('blogs.list', $category->slug)); ?>"
                      class="d-flex justify-content-between align-items-center text-dark text-decoration-none">
                      <span><?php echo e($category->name); ?></span>
                  </a>
              </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          <!-- Contact Us -->
      </div>
  </div>
  <!-- Overlay -->
  <div class="overlay" id="overlay" onclick="toggleDrawer()"></div>
  <?php
      $bottomAds = $advertisements->where('position', 'bottom')->values();
  ?>

  <?php if($bottomAds->count()): ?>
      <div class="w-100 bg-light py-3 d-flex justify-content-center">
          <div id="bottom-ad-slider" class="bottom-ad-slider">
              <?php $__currentLoopData = $bottomAds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="ad-slide" style="display: <?php echo e($index === 0 ? 'block' : 'none'); ?>;">
                      <a href="<?php echo e($ad->link); ?>" target="_blank">
                          <img src="<?php echo e(asset('admin/uploads/advertisement/' . $ad->image)); ?>" alt="<?php echo e($ad->title); ?>"
                              class="bottom-ad-image rounded">
                      </a>
                  </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
      </div>

      <style>
          .bottom-ad-slider {
              position: relative;
              width: 100%;
              max-width: 1000px;
              height: 140px;
          }

          @media (max-width: 768px) {
              .bottom-ad-slider {
                  height: 120px;
              }
          }

          @media (max-width: 576px) {
              .bottom-ad-slider {
                  height: 100px;
              }
          }

          .ad-slide {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
          }

          .bottom-ad-image {
              width: 100%;
              height: 100%;
              object-fit: contain;
              background-color: #f9f9f9;
              display: block;
              margin: 0 auto;
          }

          #backToTopBtn {
              position: fixed;
              bottom: 20px;
              right: 20px;
              z-index: 999;
              width: 45px;
              height: 45px;
              padding: 0;
              display: none;
              align-items: center;
              justify-content: center;
              transition: opacity 0.3s ease;
          }

          #backToTopBtn.show {
              display: flex;
          }
      </style>

      <script>
          document.addEventListener("DOMContentLoaded", function() {
              const slides = document.querySelectorAll('#bottom-ad-slider .ad-slide');
              let currentAdIndex = 0;

              setInterval(() => {
                  slides[currentAdIndex].style.display = 'none';
                  currentAdIndex = (currentAdIndex + 1) % slides.length;
                  slides[currentAdIndex].style.display = 'block';
              }, 3000);
          });
      </script>
  <?php endif; ?>

  <!-- Footer Section -->
  <footer class="bg-dark text-white py-5">
      <div class="container text-center">
          <div class="d-flex flex-wrap justify-content-center gap-2 py-3">
              <div class="d-flex justify-content-center flex-wrap mb-4 gap-3">
                  <?php $__currentLoopData = $footerSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if($section->type === 'footer' && !empty($section->footer_links)): ?>
                          <?php $links = json_decode($section->footer_links, true); ?>
                          <?php if(is_array($links)): ?>
                              <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <a href="<?php echo e(url($link['url'])); ?>"
                                      class="btn btn-outline-light animate-btn icon-button d-flex align-items-center text-decoration-none">
                                      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                          stroke-linecap="round" stroke-linejoin="round" class="me-1">
                                          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                      </svg>
                                      <?php echo e($link['title']); ?>

                                  </a>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                      <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
          </div>
          <!-- Footer Links Row -->
          <div class="footer-logo-container mb-4">
              <a href="<?php echo e(route('index')); ?>" class="img d-block text-center">
                  <?php if(!empty($settings->logo)): ?>
                      <img src="<?php echo e(url('/')); ?>/admin/uploads/logo/<?php echo e($settings->logo); ?>" alt="Logo"
                          style="width: 290px; height: auto;">
                  <?php else: ?>
                      <span class="h4 text-dark text-decoration-none"><?php echo e($settings->company_name); ?></span>
                  <?php endif; ?>
              </a>
          </div>
          
          <div class="social-icons-footer mb-4">
              
              <?php if(!empty($settings->facebook)): ?>
                  <a href="<?php echo e($settings->facebook); ?>" class="social-icon-link text-white" aria-label="Facebook">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-facebook">
                          <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                      </svg>
                  </a>
              <?php endif; ?>
              <?php if(!empty($settings->twitter)): ?>
                  <a href="<?php echo e($settings->twitter); ?>" class="social-icon-link text-white"
                      aria-label="X (formerly Twitter)">
                      <!-- X (formerly Twitter) logo -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round">
                          <path d="M4 4l16 16M20 4L4 20" />
                      </svg>
                  </a>
              <?php endif; ?>

              <?php if(!empty($settings->instagram)): ?>
                  <a href="<?php echo e($settings->instagram); ?>" class="social-icon-link text-white" aria-label="Instagram">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-instagram">
                          <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                          <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                          <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                      </svg>
                  </a>
              <?php endif; ?>

              <?php if(!empty($settings->linkedIn)): ?>
                  <a href="<?php echo e($settings->linkedIn); ?>" class="social-icon-link text-white" aria-label="LinkedIn">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="feather feather-linkedin">
                          <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z" />
                          <rect x="2" y="9" width="4" height="12"></rect>
                          <circle cx="4" cy="4" r="2"></circle>
                      </svg>
                  </a>
              <?php endif; ?>
          </div>
      </div>

      <!-- Floating "Go Up" Button -->
      <button id="backToTopBtn" class="btn btn-primary rounded-circle shadow-lg" title="Go to top">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-arrow-up">
              <line x1="12" y1="19" x2="12" y2="5"></line>
              <polyline points="5 12 12 5 19 12"></polyline>
          </svg>
      </button>

      <!-- Footer Bottom -->
      <div class="text-center py-3">
          <small class="text-white">
              Copyright © 2025 <?php echo e($settings->company_name); ?> Inc. All rights reserved. Registration on or use of
              this site constitutes acceptance of our Terms of Service and Privacy Policy.
          </small>
      </div>
      </div>
  </footer>
  <!-- End Fotter Section -->
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
      $(document).ready(function() {
          $('#category_id').select2({
              placeholder: "-- Select Category --",
              allowClear: true
          });
      });
  </script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
      function toggleDrawer() {
          const drawer = document.getElementById('drawer');
          const overlay = document.getElementById('overlay');
          drawer.classList.toggle('open');
          overlay.classList.toggle('show');
      }
      document.querySelectorAll('.toggle-dropdown').forEach(button => {
          button.addEventListener('click', function() {
              const submenu = this.nextElementSibling;
              const arrow = this.querySelector('.arrow');

              submenu.classList.toggle('d-none');
              arrow.textContent = submenu.classList.contains('d-none') ? '▾' : '▴';
          });
      });

      document.addEventListener("DOMContentLoaded", function() {
          const placeholders = [
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  "Search <?php echo e(addslashes($category->name)); ?>",
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          ];

          const inputs = [
              document.getElementById("searchInput"),
              document.getElementById("mobileSearchInput")
          ].filter(input => input); // remove nulls if an input is missing

          let index = 0;
          let charIndex = 0;
          let isDeleting = false;
          let currentText = "";

          function type() {
              const currentPlaceholder = placeholders[index];

              if (isDeleting) {
                  charIndex--;
                  currentText = currentPlaceholder.substring(0, charIndex);
              } else {
                  charIndex++;
                  currentText = currentPlaceholder.substring(0, charIndex);
              }

              // Update all inputs
              inputs.forEach(input => input.setAttribute("placeholder", currentText));

              let delay = isDeleting ? 50 : 150;

              if (!isDeleting && charIndex === currentPlaceholder.length) {
                  delay = 2000;
                  isDeleting = true;
              }

              if (isDeleting && charIndex === 0) {
                  isDeleting = false;
                  index = (index + 1) % placeholders.length;
                  delay = 500;
              }

              setTimeout(type, delay);
          }

          type();
      });

      document.addEventListener("DOMContentLoaded", function() {
          const backToTopBtn = document.getElementById("backToTopBtn");

          // Show/hide button on scroll
          window.addEventListener("scroll", function() {
              if (window.scrollY > 200) {
                  backToTopBtn.classList.add("show");
              } else {
                  backToTopBtn.classList.remove("show");
              }
          });

          // Scroll to top smoothly
          backToTopBtn.addEventListener("click", function() {
              window.scrollTo({
                  top: 0,
                  behavior: "smooth"
              });
          });
      });
  </script>

  <?php echo $__env->yieldPushContent('script'); ?>
  </body>

  </html>
<?php /**PATH C:\xampp\htdocs\Laravel_Projects\Bookmarking\resources\views/layouts/footer.blade.php ENDPATH**/ ?>