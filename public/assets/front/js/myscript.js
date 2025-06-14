$(function ($) {
  "use strict";

  function lazy() {
    $(".lazy").Lazy({
      scrollDirection: "vertical",
      effect: "fadeIn",
      effectTime: 1000,
      threshold: 0,
      visibleOnly: false,
      onError: function (element) {
        console.log("error loading " + element.data("src"));
      },
    });
  }

  $(document).ready(function () {
    lazy();

    function number_format(number, decimals = 2, dec_point, thousands_sep) {
      // Strip all characters but numerical ones.
      number = (number + "").replace(/[^0-9+\-Ee.]/g, "");
      var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
        dec = typeof dec_point === "undefined" ? "." : dec_point,
        s = "",
        toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec);
          return "" + Math.round(n * k) / k;
        };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
      if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || "").length < prec) {
        s[1] = s[1] || "";
        s[1] += new Array(prec - s[1].length + 1).join("0");
      }
      return s.join(dec);
    }

    // announcement banner magnific popup
    if (mainbs.is_announcement == 1) {
      $(".announcement-banner").magnificPopup({
        type: "inline",
        midClick: true,
        mainClass: "mfp-fade",
        callbacks: {
          open: function () {
            $.magnificPopup.instance.close = function () {
              // Do whatever else you need to do here
              sessionStorage.setItem("announcement", "closed");
              // console.log(sessionStorage.getItem('announcement'));

              // Call the original close method to close the announcement
              $.magnificPopup.proto.close.call(this);
            };
          },
        },
      });
    }

    // Mobile Category
    $("#category_list .has-children .category_search span").on(
      "click",
      function (e) {
        e.preventDefault();
      }
    );

    // Toggle mobile serch
    $(".close-m-serch").on("click", function () {
      $(".topbar .search-box-wrap").toggleClass("d-none");
    });

    // Flash Deal Area Start
    var $hero_slider_main = $(".hero-slider-main");
    $hero_slider_main.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      smartSpeed: 100,
      items: 1,
      thumbs: false,
      margin: 15,
      responsive: {
        0: { items: 1 },
        576: { items: 1 },
        768: { items: 1 },
        992: { items: 2 },
        1200: { items: 2 },
      },
    });

    var $category_slider_main = $(".category-slider");
    $category_slider_main.owlCarousel({
      navText: [],
      center: true,
      nav: true,
      dots: false,
      loop: true,
      margin: 15,
      autoplay: true,
      autoplayTimeout: 4000,
      smartSpeed: 100,
      responsive: {
        0: { items: 2 },
        576: { items: 3 },
        768: { items: 4 },
        992: { items: 5 },
        1200: { items: 6 },
      },
    });

    // heroarea-slider
    var $testimonialSlider = $(".heroarea-slider");
    $testimonialSlider.owlCarousel({
      loop: true,
      navText: [],
      nav: true,
      nav: true,
      dots: false,
      autoplay: true,
      thumbs: false,
      autoplayTimeout: 5000,
      smartSpeed: 1200,
      responsive: {
        0: {
          items: 1,
          nav: false,
        },
        576: {
          items: 1,
        },
        950: {
          items: 1,
        },
        960: {
          items: 1,
        },
        1200: {
          items: 1,
        },
      },
    });

    // popular_category_slider
    var $popular_category_slider = $(".popular-category-slider");
    $popular_category_slider.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      loop: false,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 2,
        },
        576: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 4,
        },
        1200: {
          items: 4,
        },
        1400: {
          items: 5,
        },
      },
    });

    // Flash Deal Area Start
    var $flash_deal_slider = $(".flash-deal-slider");
    $flash_deal_slider.owlCarousel({
      navText: [],
      nav: false,
      dots: false,
      autoplay: true,
      autoplayTimeout: 3000,
      smartSpeed: 100,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 2,
          margin: 10,
          dots: true,
        },
        576: {
          items: 2,
          margin: 10,
          dots: true,
        },
        768: {
          items: 2,
          margin: 10,
          dots: true,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 5,
        },
        1400: {
          items: 5,
        },
      },
    });

    // col slider
    var $col_slider = $(".newproduct-slider");
    $col_slider.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      loop: false,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 1,
        },
        530: {
          items: 1,
        },
      },
    });

    // col slider 2
    var $col_slider2 = $(".toprated-slider");
    $col_slider2.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      loop: true,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 1,
        },
        530: {
          items: 1,
        },
      },
    });

    // newproduct-slider Area Start
    var $newproduct_slider = $(".features-slider");
    $newproduct_slider.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      loop: false,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 2,
        },
        576: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 4,
        },
        1200: {
          items: 4,
        },
        1400: {
          items: 5,
        },
      },
    });

    // home-blog-slider
    var $home_blog_slider = $(".home-blog-slider");
    $home_blog_slider.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      loop: false,
      thumbs: false,
      margin: 15,
      responsive: {
        0: {
          items: 1,
        },
        576: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
        1400: {
          items: 4,
        },
      },
    });

    // brand-slider
    var $brand_slider = $(".brand-slider");
    $brand_slider.owlCarousel({
      navText: [],
      nav: true,
      dots: false,
      autoplayTimeout: 6000,
      smartSpeed: 1200,
      loop: true,
      thumbs: false,
      margin: 15,
      responsive: {
        0: {
          items: 2,
        },
        575: {
          items: 3,
        },
        790: {
          items: 4,
        },
        1100: {
          items: 4,
        },
        1200: {
          items: 4,
        },
        1400: {
          items: 5,
        },
      },
    });

    // toprated-slider Area Start
    var $relatedproductsliderv = $(".relatedproductslider");
    $relatedproductsliderv.owlCarousel({
      nav: false,
      dots: true,
      autoplay: true,
      autoplayTimeout: 3000,
      smartSpeed: 100,
      margin: 15,
      thumbs: false,
      responsive: {
        0: {
          items: 2,
        },
        576: {
          items: 2,
        },
        768: {
          items: 3,
        },
        992: {
          items: 4,
        },
        1200: {
          items: 4,
        },
        1400: {
          items: 5,
        },
      },
    });

    // product thumnail slider
    var $thumbSlider = $(".product-thumbnails-slider").owlCarousel({
      nav: false,
      dots: true,
      autoplay: false,
      smartSpeed: 1200,
      margin: 15,
      thumbs: false,
      responsive: {
        0: { items: 5 },
        576: { items: 5 },
        768: { items: 5 },
        992: { items: 4 },
        1200: { items: 5 },
        1400: { items: 5 },
      },
    });

    // $("#main-product-image").on("click", function () {
    //   $(this).toggleClass("zoomed");
    // });

    // grab the carousel API
    var owl = $thumbSlider.data("owl.carousel");

    // 2) helper to swap main image without flash
    function changeMainImage(newSrc, newIndex) {
      const mainImage = $("#main-product-image");
      const nextImage = $(".product-main-image-next");

      // Preload the new image
      const img = new Image();
      img.src = newSrc;

      img.onload = function () {
        // Set the next image source
        nextImage.find("img").attr("src", newSrc);

        // Start the transition
        nextImage.css("transform", "translateX(0)");
        mainImage.addClass("ani-out");

        // After animation completes, update main image and reset
        setTimeout(() => {
          mainImage.attr("src", newSrc);
          mainImage.removeClass("ani-out");
          nextImage.css("transform", "translateX(100%)");

          // Update active state of thumbnails
          $(".thumbnail-item").removeClass("active");
          $(`.thumbnail-item[data-index="${newIndex}"]`).addClass("active");
        }, 300);
      };
    }

    // 3) arrow clicks
    $("#next-image, #prev-image").on("click", function () {
      if ($(this).hasClass("disabled")) return;

      var isNext = this.id === "next-image",
        curr = parseInt($(".thumbnail-item.active").data("index")) || 0,
        total = $(".thumbnail-item").length,
        newIdx = isNext ? (curr + 1) % total : (curr - 1 + total) % total,
        newSrc = $('.thumbnail-item[data-index="' + newIdx + '"]').data(
          "image"
        );

      // swap the main image
      changeMainImage(newSrc, newIdx);

      // now only scroll carousel if newIdx is out of view
      var width = $(window).width(),
        opts = owl.options.responsive[width] || owl.options.responsive[0],
        perPage = opts.items,
        firstVisible = owl.current(),
        lastVisible = firstVisible + perPage - 1;

      if (newIdx < firstVisible || newIdx > lastVisible) {
        $thumbSlider.trigger("to.owl.carousel", [newIdx, 300]);
      }
    });

    // Similar approach for thumbnail clicks
    $(".thumbnail-item").on("click", function () {
      if ($(this).hasClass("active") || $(".nav-arrow").hasClass("disabled"))
        return;

      var newIndex = $(this).data("index");
      var currentIndex = parseInt(
        $(".thumbnail-item.active").data("index") || 0
      );
      var isNext = newIndex > currentIndex;
      var newImageSrc = $(this).data("image");

      // Disable buttons during animation
      $(".nav-arrow").addClass("disabled");

      // Create new image element and wait for it to load
      var $newImage = $("<img>", {
        src: newImageSrc,
        alt: "zoom",
        class: "product-main-image-next",
      });

      // Preload the image first
      $newImage
        .on("load", function () {
          var $mainImage = $("#main-product-image");
          var $container = $(".product-main-image-container");

          // Add the new image to the container but keep it hidden
          $container.append($newImage);

          // Position the new image absolutely over the current one
          $newImage.css({
            position: "absolute",
            top: "0",
            left: "0",
            width: "100%",
            height: "auto",
            "z-index": "2",
            opacity: "0",
          });

          // Now start the animations
          if (isNext) {
            $mainImage.addClass("animate__animated animate__slideOutLeft");
            $newImage.addClass("animate__animated animate__slideInRight");
          } else {
            $mainImage.addClass("animate__animated animate__slideOutRight");
            $newImage.addClass("animate__animated animate__slideInLeft");
          }

          // Make the new image visible
          $newImage.css("opacity", "1");

          // After animation completes, replace old image and clean up
          setTimeout(function () {
            // Update the main image source
            $mainImage.attr("src", newImageSrc);

            // Remove animation classes
            $mainImage.removeClass(
              "animate__animated animate__slideOutLeft animate__slideOutRight"
            );

            // Remove the temporary image
            $newImage.remove();

            // Update active thumbnail
            $(".thumbnail-item").removeClass("active");
            $('.thumbnail-item[data-index="' + newIndex + '"]').addClass(
              "active"
            );

            // Re-enable buttons
            $(".nav-arrow").removeClass("disabled");
          }, 500);
        })
        .on("error", function () {
          // In case the image fails to load
          console.error("Failed to load image:", newImageSrc);
          $(".nav-arrow").removeClass("disabled");
        });
    });

    // Handle thumbnail click
    $(".thumbnail-item").on("click", function () {
      if ($(this).hasClass("active") || $(".nav-arrow").hasClass("disabled"))
        return;

      var newIndex = $(this).data("index");
      var currentIndex = parseInt(
        $(".thumbnail-item.active").data("index") || 0
      );
      var isNext = newIndex > currentIndex;
      var newImageSrc = $(this).data("image");

      // Disable buttons during animation
      $(".nav-arrow").addClass("disabled");

      // Update main image with animation
      var $mainImage = $("#main-product-image");

      // Remove any existing animation classes
      $mainImage.removeClass(
        "animate__animated animate__slideInLeft animate__slideInRight animate__slideOutLeft animate__slideOutRight"
      );

      // Apply appropriate animations based on direction
      if (isNext) {
        $mainImage.addClass("animate__animated animate__slideOutLeft");
      } else {
        $mainImage.addClass("animate__animated animate__slideOutRight");
      }

      // Wait for animation to complete
      setTimeout(function () {
        // Change image source
        $mainImage.attr("src", newImageSrc);

        // Remove outgoing and apply incoming animation
        $mainImage.removeClass("animate__slideOutLeft animate__slideOutRight");

        if (isNext) {
          $mainImage.addClass("animate__slideInRight");
        } else {
          $mainImage.addClass("animate__slideInLeft");
        }

        // Update active thumbnail
        $(".thumbnail-item").removeClass("active");
        $('.thumbnail-item[data-index="' + newIndex + '"]').addClass("active");

        // Re-enable buttons after animation completes
        setTimeout(function () {
          $(".nav-arrow").removeClass("disabled");
        }, 1000);
      }, 500); // Match animation duration
    });

    // Set first thumbnail as active by default
    $(".thumbnail-item:first-child").addClass("active");

    // Blog Details Slider Area Start
    var $hero_slider_main = $(".blog-details-slider");
    $hero_slider_main.owlCarousel({
      navText: [],
      nav: true,
      dots: true,
      loop: true,
      autoplay: true,
      autoplayTimeout: 5000,
      smartSpeed: 1200,
      items: 1,
      thumbs: false,
    });

    // Recent Blog Slider Area Start
    var $popular_category_slider = $(".resent-blog-slider");
    $popular_category_slider.owlCarousel({
      navText: [],
      nav: false,
      dots: true,
      loop: false,
      autoplayTimeout: 5000,
      smartSpeed: 1200,
      margin: 30,
      thumbs: false,
      responsive: {
        0: {
          items: 1,
        },
        576: {
          items: 2,
        },
        768: {
          items: 2,
        },
        992: {
          items: 3,
        },
        1200: {
          items: 3,
        },
      },
    });

    // Product details main slider
    $(".product-details-slider").owlCarousel({
      loop: true,
      items: 1,
      autoplayTimeout: 5000,
      smartSpeed: 1200,
      autoplay: false,
      thumbs: true,
      dots: false,
      thumbImage: true,
      animateOut: "fadeOut",
      animateIn: "fadeIn",
      thumbContainerClass: "owl-thumbs",
      thumbItemClass: "owl-thumb-item",
    });

    // Product details image zoom
    $(".product-details-slider .item").zoom();

    // Video popup
    $(".video-btn a").magnificPopup({
      type: "iframe",
      mainClass: "mfp-fade",
    });

    $(".left-category-area .category-header").on("click", function () {
      $(".left-category-area .category-list").toggleClass("active");
    });

    $("[data-date-time]").each(function () {
      var $this = $(this),
        finalDate = $(this).attr("data-date-time");
      $this.countdown(finalDate, function (event) {
        $this.html(
          event.strftime(
            `<span>%D<small>${language.Days}</small></span></small> <span>%H<small>${language.Hrs}</small></span> <span>%M<small>${language.Min}</small></span> <span>%S<small>${language.Sec}</small></span>`
          )
        );
      });
    });

    // Subscriber Form Submit
    $(document).on("submit", ".subscriber-form", function (e) {
      e.preventDefault();
      var $this = $(this);
      var submit_btn = $this.find("button");
      submit_btn.find(".fa-spin").removeClass("d-none");
      $this.find("input[name=email]").prop("readonly", true);
      submit_btn.prop("disabled", true);
      $.ajax({
        method: "POST",
        url: $(this).prop("action"),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          if (data.errors) {
            for (var error in data.errors) {
              dangerNotification(data.errors[error]);
            }
          } else {
            if ($this.hasClass("subscription-form")) {
              $(".close-popup").click();
            }
            successNotification(data);
            $this.find("input[name=email]").val("");
          }
          submit_btn.find(".fa-spin").addClass("d-none");
          $this.find("input[name=email]").prop("readonly", false);
          submit_btn.prop("disabled", false);
        },
      });
    });
    // Subscriber Form Submit ENDS

    // Notifications
    function successNotification(title) {
      $.notify(
        {
          title: ` <strong>${title}</strong>`,
          message: "",
          icon: "fas fa-check-circle",
        },
        {
          // settings
          element: "body",
          position: null,
          type: "success",
          allow_dismiss: true,
          newest_on_top: false,
          showProgressbar: false,
          placement: {
            from: "top",
            align: "right",
          },
          offset: 20,
          spacing: 10,
          z_index: 1031,
          delay: 5000,
          timer: 1000,
          url_target: "_blank",
          mouse_over: null,
          animate: {
            enter: "animated fadeInDown",
            exit: "animated fadeOutUp",
          },
          onShow: null,
          onShown: null,
          onClose: null,
          onClosed: null,
          icon_type: "class",
        }
      );
    }

    function dangerNotification(title) {
      $.notify(
        {
          // options
          title: ` <strong>${title}</strong>`,
          message: "",
          icon: "fas fa-exclamation-triangle",
        },
        {
          // settings
          element: "body",
          position: null,
          type: "danger",
          allow_dismiss: true,
          newest_on_top: false,
          showProgressbar: false,
          placement: {
            from: "top",
            align: "right",
          },
          offset: 20,
          spacing: 10,
          z_index: 1031,
          delay: 5000,
          timer: 1000,
          url_target: "_blank",
          mouse_over: null,
          animate: {
            enter: "animated fadeInDown",
            exit: "animated fadeOutUp",
          },
          onShow: null,
          onShown: null,
          onClose: null,
          onClosed: null,
          icon_type: "class",
        }
      );
    }
    // Notifications Ends

    $(document).on("click", ".list-view", function () {
      let viewCheck = $(this).attr("data-step");
      let check = $(this);
      $(".list-view").removeClass("active");
      $("#search_form #view_check").val(viewCheck);
      $("#search_button").click();
      check.addClass("active");
    });

    // category wise product
    $(document).on("click", ".category_get,.product_get", function () {
      $("." + this.className).removeClass("active");
      $(this).addClass("active");
      let geturl = $(this).attr("data-href");
      let view = $(this).attr("data-target");

      $("." + view).removeClass("d-none");

      $.get(geturl, function (response) {
        $("#" + view).html(response);
        $("." + view).addClass("d-none");

        if (response.data === undefined) {
          $("." + view + "_not_found").removeClass("d-none");
        } else {
          $("." + view + "_not_found").addClass("d-none");
        }
      });
    });

    // product quintity select js Start
    $(document).on("click", ".subclick", function () {
      let current_qty = parseInt($(".cart-amount").val());
      if (current_qty > 1) {
        $(".cart-amount").val(current_qty - 1);
      } else {
        error("Minumum Quantity Must Be 1");
      }
    });

    // product quintity select js Start
    $(document).on("click", ".addclick", function () {
      let current_stock = parseInt($("#current_stock").val());
      let current_qty = parseInt($(".cart-amount").val());
      if (current_qty < current_stock) {
        $(".cart-amount").val(current_qty + 1);
      } else {
        error("Product Quantity Maximum " + current_stock);
      }
    });

    $(document).on("keyup", ".cart-amount", function () {
      let current_stock = parseInt($("#current_stock").val());
      let key_val = parseInt($(this).val());

      if (key_val > current_stock) {
        error("Product Maximum Quantity " + current_stock);
        $(".cart-amount").val(current_stock);
      }
      if (key_val <= 0) {
        $(".cart-amount").val(1);
        error("Product Minimum Quantity" + 1);
      }
      if (key_val > 0 && key_val < current_stock) {
        $(".cart-amount").val(key_val);
      }
    });

    $(document).on("click", ".wishlist_store", function (e) {
      e.preventDefault();
      let wishlist_url = $(this).attr("href");
      $.get(wishlist_url, function (response) {
        if (response.status == 0) {
          location.href = response.link;
        } else if (response.status == 2) {
          dangerNotification(response.message);
        } else {
          $(".wishlist1").addClass("d-none");
          $(".wishlist2").removeClass("d-none");
          $(".wishlist_count").text(response.count);
          successNotification(response.message);
        }
      });
    });

    // catalog js start
    $(document).on("click", ".brand-select", function () {
      $(".brand-select").prop("checked", false);
      let brand = $(this).val();
      $(this).prop("checked", true);
      $("#search_form #brand").val(brand);
      removePage();
      $("#search_button").click();
    });

    $(document).on("click", "#price_filter", function () {
      let min_price = parseInt($(".min_price").html());
      let max_price = parseInt($(".max_price").html());
      $("#search_form #minPrice").val(min_price);
      $("#search_form #maxPrice").val(max_price);
      removePage();
      $("#search_button").click();
    });

    $(document).on("change", "#sorting", function () {
      let sorting = $(this).val();
      $("#search_form #sorting").val(sorting);
      removePage();
      $("#search_button").click();
    });

    $(document).on("click", ".widget_price_filter", function () {
      let filter_prices = $(this).val();
      if (filter_prices) {
        filter_prices = filter_prices.split(",");
        $("#search_form #minPrice").val(filter_prices[0]);
        $("#search_form #maxPrice").val(filter_prices[1]);
      } else {
        $("#search_form #minPrice").val("");
        $("#search_form #maxPrice").val("");
      }
      removePage();
      $("#search_button").click();
    });

    $(document).on("change", "#category_select", function () {
      let category = $(this).val();
      $("#search__category").val(category);
    });

    $(document).on("click", "#quick_filter li a", function () {
      $("#quick_filter li").removeClass("active");
      let filter = "";
      $(this).parent().addClass("active");
      if ($(this).attr("data-href")) {
        filter = $(this).attr("data-href");
      } else {
        filter = $(this).attr("data-href");
      }
      $("#search_form #quick_filter").val(filter);
      removePage();
      $("#search_button").click();
    });

    function removePage() {
      $("#search_form #page").val("");
    }

    $(document).on("keyup", "#__product__search", function () {
      let search = $(this).val();
      let category = "";
      category = $("#search__category").val();
      if (search) {
        let url = $(this).attr("data-target");
        $.get(
          url + "?search=" + search + "&category=" + category,
          function (response) {
            $(".serch-result").removeClass("d-none");
            $(".serch-result").html(response);
          }
        );
      } else {
        $(".serch-result").addClass("d-none");
      }
    });
    $(document).on("click", "#view_all_search_", function () {
      $("#header_search_form").submit();
    });

    $(document).on("click", "#category_list li a.category_search", function () {
      $("#category_list li").removeClass("active");
      let category = "";
      $(this).parent().addClass("active");
      if ($(this).attr("data-href")) {
        category = $(this).attr("data-href");
      } else {
        category = $(this).attr("data-href");
      }
      removePage();
      $("#search_form #childcategory").val("");
      $("#search_form #subcategory").val("");
      $("#search_form #category").val(category);
      $("#search_button").click();
    });

    $(document).on("click", "#subcategory_list li a.subcategory", function () {
      $("#subcategory_list li").removeClass("active");
      let category = "";
      $(this).parent().addClass("active");
      if ($(this).attr("data-href")) {
        category = $(this).attr("data-href");
      } else {
        category = $(this).attr("data-href");
      }
      $("#search_form #childcategory").val("");
      $("#search_form #subcategory").val(category);
      $("#search_button").click();
    });

    $(document).on(
      "click",
      "#childcategory_list li a.childcategory",
      function () {
        $("#childcategory_list li").removeClass("active");
        let childcategory = "";
        $(this).parent().addClass("active");
        if ($(this).attr("data-href")) {
          childcategory = $(this).attr("data-href");
        } else {
          childcategory = $(this).attr("data-href");
        }
        removePage();
        $("#search_form #childcategory").val(childcategory);
        $("#search_button").click();
      }
    );

    $(document).on(
      "click",
      "#item_pagination .page-item .page-link",
      function (e) {
        e.preventDefault();
        let pagination = $(this).text();
        let lastActive = parseInt(
          $("#item_pagination .page-item.active .page-link").text()
        );
        if (pagination == "›") {
          pagination = lastActive + 1;
        } else if (pagination == "‹") {
          pagination = lastActive - 1;
        }
        $("#search_form #page").val(pagination);
        $("#search_button").click();
      }
    );

    $(document).on("submit", "#search_form", function (e) {
      e.preventDefault();

      let loader = `
            <div id="view_loader_div" class="">
            <div class="product-not-found">
              <img class="loader_image" src="${
                mainurl + "/assets/images/ajax_loader.gif"
              }" alt="">
            </div>
          </div>
            `;
      $("#list_view_ajax").html(loader);

      let form_url = $(this).attr("action");
      let method = $(this).attr("method");
      $.ajax({
        type: method,
        url: form_url,
        data: $(this).serialize(),
        success: function (data) {
          window.scrollTo(0, 0);
          $("#list_view_ajax").html(data);
        },
      });
    });

    // catalog script end

    // rating from submit
    $(".ratingForm").on("submit", function (e) {
      e.preventDefault();
      var $this = $(this);
      var submit_btn = $this.find("button");
      submit_btn.find(".fa-spin").removeClass("d-none");
      $this.find("textarea").prop("readonly", true);
      submit_btn.prop("disabled", true);
      $.ajax({
        method: "POST",
        url: $(this).prop("action"),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (data) {
          if (data.errors) {
            for (var error in data.errors) {
              dangerNotification(data.errors[error]);
            }
          } else {
            $this.find("textarea").prop("readonly", false);
            submit_btn.prop("disabled", false);
            $(".modal_close").click();

            successNotification(data);
            $this.find("textarea").val("");
            setTimeout(function () {
              window.location.reload();
            }, 1000);
          }
        },
      });
    });
    // compare script start

    $(document).on("click", ".product_compare", function () {
      let compare_url = $(this).attr("data-target");
      $.get(compare_url, function (data) {
        if (data.status == 1) {
          successNotification(data.message);
        } else {
          dangerNotification(data.message);
        }
        $(".compare_count").text(data.compare_count);
      });
    });

    $(document).on("click", ".compare_remove", function () {
      let removeUrl = $(this).attr("data-href");
      $.get(removeUrl, function () {
        location.reload();
      });
    });
    // compare script end

    $(document).on("keyup", ".cart-amount", function () {
      getData();
    });
    $(document).on("click", ".increaseQty", function () {
      getData();
    });
    $(document).on("click", ".increaseQtycart", function () {
      let item_key = $(this).attr("data-target");
      let item_id = $(this).attr("data-id");
      let item = $(this).attr("data-item");
      let newOptionArray = item.split(",");
      let qty = parseInt($(this).parent().find("input").val()) + 1;
      cartSubmit(item_key, item_id, qty, newOptionArray);
      // getData(0,0,0,0,0,);
    });

    $(document).on("click", ".decreaseQty", function () {
      getData();
    });

    $(document).on("click", ".decreaseQtycart", function () {
      let item_key = $(this).attr("data-target");
      let item_id = $(this).attr("data-id");
      let qty = parseInt($(this).parent().find("input").val()) - 1;

      if (qty > 0) {
        cartSubmit(item_key, item_id, qty);
        getData();
      }
    });

    $(document).on("click", "#add_to_cart", function () {
      getData(1);
    });
    $(document).on("click", "#but_to_cart", function () {
      getData(1, 0, 0, 0, 1);
    });
    $(document).on("click", ".add_to_single_cart", function () {
      getData(1, $(this).attr("data-target"));
    });

    function cartSubmit(item_key, item_id, cartQty, newOptionArray) {
      getData(1, item_key, item_id, cartQty, 0, newOptionArray);
    }

    function getData(
      status = 0,
      check = 0,
      item_key = 0,
      qty = 0,
      add_type = 0,
      optionIds = null
    ) {
      let itemId;
      let type;
      if (check != 0) {
        itemId = check;
        type = 1;
      } else {
        itemId = $("#item_id").val();
        type = 0;
      }

      let options_prices = optionPrice();

      let totalOptionPrice = parseFloat(optionPriceSum(options_prices));

      let attribute_ids = $('input[type=radio][name^="attribute_"]:checked')
        .map(function () {
          return $(this).data("type");
        })
        .get();

      if (optionIds != null) {
        var options_ids = optionIds;
      } else {
        var options_ids = $('input[type=radio][name^="attribute_"]:checked')
          .map(function () {
            return $(this).data("href");
          })
          .get();
      }

      let quantity = parseInt(getQuantity());

      if (isNaN(quantity)) {
        quantity = 1;
      }
      let selectedStock = null;
      $('input[type=radio][name^="attribute_"]:checked').each(function () {
        const stock = parseInt($(this).data("stock"));
        if (selectedStock === null || stock < selectedStock) {
          selectedStock = stock;
        }
      });
      if (qty != 0) {
        quantity = qty;
      }

      let setCurrency = $("#set_currency").val();

      let currency_direction = $("#currency_direction").val();

      let hasAttributes = $(".attribute_option").length > 0;
      let demoPrice = parseFloat($("#demo_price").val());
      let subPrice;

      if (hasAttributes) {
        subPrice = totalOptionPrice; // Use only selected options price
      } else {
        subPrice = demoPrice; // Use base product price
      }

      let mainPrice = subPrice * quantity;

      mainPrice = number_format(
        mainPrice,
        2,
        decimal_separator,
        thousand_separator
      );

      // if (currency_direction == 0) {
      //   $("#main_price").html(mainPrice + setCurrency);
      // } else {
      //   $("#main_price").html(setCurrency + mainPrice);
      // }

      // console.log("🛒 Debug Info:");
      // console.log("Item ID:", itemId);
      // console.log("Option IDs:", options_ids);
      // console.log("Attribute IDs:", attribute_ids);
      // console.log("Quantity:", quantity);
      // console.log("Main Price (demoPrice):", demoPrice);
      // console.log("Total Option Price:", totalOptionPrice);
      // console.log("Final Sub Price:", subPrice);
      // console.log("Total Price x Qty:", mainPrice);

      // ✅ Update stock & quantity dropdown
      let stock = 0;

      // Collect stock and price data
      $('input[type=radio][name^="attribute_"]:checked').each(function () {
        const attrStock = parseInt($(this).data("stock"));
        if (stock === 0 || attrStock < stock) {
          stock = attrStock;
        }
      });

      // Update dynamic stock label
      $("#dynamic_stock").html(
        stock > 0
          ? `<span class="text-success d-inline-block">In Stock <b>(${stock} items)</b></span>`
          : `<span class="text-danger d-inline-block">Out of Stock</span>`
      );

      // Update current_stock hidden input
      $("#current_stock").val(stock);

      // Regenerate quantity dropdown
      const $quantitySelect = $("#quantity");
      $quantitySelect.empty();
      for (let i = 1; i <= stock; i++) {
        $quantitySelect.append(`<option value="${i}">${i}</option>`);
      }

      if (status == 1) {
        let addToCartUrl = `${mainurl}/product/add/cart?item_id=${itemId}&options_ids=${options_ids}&attribute_ids=${attribute_ids}&quantity=${quantity}&type=${type}&item_key=${item_key}&add_type=${add_type}`;
        $.ajax({
          method: "GET",
          contentType: false,
          cache: false,
          processData: false,
          url: addToCartUrl,
          success: function (data) {
            if (data.status == "outStock") {
              dangerNotification(data.message);
            } else if (data.status == "alreadyInCart") {
              dangerNotification(data.message);
            } else {
              $(".cart_count").text(data.qty);
              $(".cart_view_header").load(
                $("#header_cart_load").attr("data-target")
              );
              if (qty) {
                $("#view_cart_load").load(
                  $("#cart_view_load").attr("data-target")
                );
              }
              if (add_type == 1) {
                location.href = mainurl + "/checkout/billing/address";
              } else {
                successNotification(data.message);
              }
            }
          },
        });
      }
    }

    function optionPrice() {
      let options_prices = $('input[type=radio][name^="attribute_"]:checked')
        .map(function () {
          return $(this).data("target");
        })
        .get();

      return options_prices;
    }

    function getQuantity() {
      return parseInt($("#quantity").val()); // since your select has id="quantity"
    }

    function optionPriceSum(options_prices) {
      var price = 0;
      $.each(options_prices, function (i, v) {
        price += parseFloat(v);
      });
      return price;
    }

    // cart script end
    $(document).on("submit", "#coupon_form", function (e) {
      e.preventDefault();

      var form = $(this);
      var url = form.attr("action");
      $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        success: function (data) {
          if (data.status == true) {
            successNotification(data.message);
            $("#cart_view_load").load($("#cart_view_load").attr("data-target"));
          } else {
            dangerNotification(data.message);
          }
        },
      });
    });

    // user panel script start
    $(document).on("change", "#avater", function () {
      var file = event.target.files[0];
      var reader = new FileReader();
      reader.onload = function (e) {
        $("#avater_photo_view").attr("src", e.target.result);
      };
      reader.readAsDataURL(file);
    });

    $("#submit_number").on("click", function (e) {
      var link =
        $(this).data("href") + "?order_number=" + $("#order_number").val();
      $("#track-order").load(link);
      return false;
    });
  });
});

// state price set up

$(document).on("change", "#state_id_select", function () {
  var url = $("option:selected", this).attr("data-href");
  var state_id = $(this).val();
  var shipping_id = $("#shipping_id_select option:selected").val();
  url = url + "?state_id=" + state_id + "&shipping_id=" + shipping_id;
  $.get(url, function (response) {
    $(".set__state_price_tr").removeClass("d-none");
    $(".set__state_price").text(response.state_price);
    $(".grand_total_set").text(response.grand_total);
    $(".state_id_setup").val(state_id);
    $(".state_message").addClass("d-none");
  });
});

$(document).on("change", "#shipping_id_select", function () {
  var url = $("option:selected", this).attr("data-href");
  var state_id = $("#state_id_select option:selected").val();
  var shipping_id = $(this).val();
  url = url + "?state_id=" + state_id + "&shipping_id=" + shipping_id;
  $.get(url, function (response) {
    $(".set__shipping_price_tr").removeClass("d-none");
    $(".set__shipping_price").text(response.shipping_price);
    $(".grand_total_set").text(response.grand_total);
    $(".shipping_id_setup").val(shipping_id);
    $(".shipping_message").addClass("d-none");
  });
});

$(document).on("click", "#trams__condition", function () {
  if ($(this).is(":checked")) {
    $("#continue__button").attr("type", "submit");
    $("#continue__button").prop("disabled", false);
  } else {
    $("#continue__button").attr("type", "button");
    $("#continue__button").prop("disabled", true);
  }
});

$(window).on("load", function (event) {
  // Preloader
  $("#preloader").fadeOut(500);
  // announcement
  if (mainbs.is_announcement == 1) {
    // trigger announcement banner base on sessionStorage
    let announcement =
      sessionStorage.getItem("announcement") != null ? false : true;
    if (announcement) {
      setTimeout(function () {
        $(".announcement-banner").trigger("click");
      }, mainbs.announcement_delay * 1000);
    }
  }
});
