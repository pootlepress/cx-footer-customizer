(function ($) {

    $(document).ready(function () {
        if (typeof PPFooterCustomizer != 'undefined') {


            $(window).resize(function () {
                footerCustomizerSticky();
            });

            // define this function first, before it is called,
            // or else it will cause js error in Firefox
            function footerCustomizerSticky() {
                var $footerElement = null;
                var $footerWidgetAreaElement = null;
                if (PPFooterCustomizer.isFooterFullWidth) {
                    $footerElement = $('#footer-container');
                    $footerWidgetAreaElement = $('#footer-widgets-container');
                } else {
                    $footerElement = $('#footer');
                    $footerWidgetAreaElement = $('#footer-widgets');
                }

                if ($('body')[0].clientWidth >= 768) {

                    // if footer widget area is sticky, sticky the bottom part too
                    if ((PPFooterCustomizer.stickyWidgetAreaDesktop && PPFooterCustomizer.isFooterFullWidth)
                        || (PPFooterCustomizer.stickyFooterDesktop && PPFooterCustomizer.isFooterFullWidth)) {
                        var footerHeight = $footerElement.outerHeight();

                        var marginBottom = 0;
                        if (PPFooterCustomizer.isFooterFullWidth) {
                            marginBottom = footerHeight;
                        } else {
                            marginBottom = footerHeight - 28; // this 28px is original bottom space below the footer
                        }

                        $footerWidgetAreaElement.css('margin-bottom', marginBottom + 'px');

                        $('#content').css('margin-bottom', $footerWidgetAreaElement.outerHeight(true) + 'px');
                    } else {
                        $footerWidgetAreaElement.css('margin-bottom', '0px');
                    }
                } else {
                    // if footer widget area is sticky, sticky the bottom part too
                    if ((PPFooterCustomizer.stickyWidgetAreaMobile && PPFooterCustomizer.isFooterFullWidth)
                        || PPFooterCustomizer.stickyFooterMobile) {
                        var footerHeight = $footerElement.outerHeight();

                        var marginBottom = footerHeight;

                        $footerWidgetAreaElement.css('margin-bottom', marginBottom + 'px');
                    } else {
                        $footerWidgetAreaElement.css('margin-bottom', '0px');
                    }

                }
            }

            footerCustomizerSticky();

        }
    });

})(jQuery);

