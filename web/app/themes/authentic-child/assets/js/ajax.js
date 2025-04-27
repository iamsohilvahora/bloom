jQuery(document).ready(function(){
    var page = 1;
    // Load post on click of load more button
    jQuery('.load-btn').on('click' , function(){
		postListFilter(this);
    });
    var pageNumber = 1;
    function postListFilter(obj){
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination');
        let loadMoreText = jQuery(obj).text();
        let post_per_page = jQuery(obj).attr('data-total-post');
        let category_id = jQuery(obj).attr('data-category-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        if(loadMoreText != ''){
            pageNumber++;
        }
        else{
            pageNumber = 1;
        }
        jQuery('div.sight-portfolio-area__main').addClass('sight-portfolio-loading'); // add loader
        jQuery('.load-btn').text('Loading'); // add loading text
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_posts', 
                post_per_page : post_per_page,
                category_id : category_id,
                pageNumber : pageNumber,
                load_more_post : "load_more_post",
            },
            dataType: "json",
            success: function(data){
                jQuery('div.sight-portfolio-area__main').removeClass('sight-portfolio-loading'); // remove loader
                jQuery('.load-btn').text('Load More'); // add load more text
                if(loadMoreText != ''){
                    jQuery(".sight-portfolio-area__main").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                }
                else{
                    jQuery(".sight-portfolio-area__main").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                    else{
                        loadMoreButton.show();
                    } 
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;　
    }

    // Load post on click of sub-category list
    jQuery('.sight-category-list').on('click', function(e){
        e.preventDefault();
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination');
        let loadMoreText = jQuery('.load-btn').text();
        let post_per_page = jQuery('.load-btn').attr('data-total-post');
        let list_category_id = jQuery(this).attr('data-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        pageNumber = 1;
        jQuery('.load-btn').attr('data-category-id', list_category_id); // Change category-id of load more button
        jQuery('div.sight-portfolio-area__main').addClass('sight-portfolio-loading'); // add loader
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_posts', 
                post_per_page : post_per_page,
                category_id : list_category_id,
                pageNumber : pageNumber,
                load_more_post : "load_category_post",
            },
            dataType: "json",
            success: function(data){
                jQuery(".sight-portfolio-area__main").html(data.content);
                jQuery('div.sight-portfolio-area__main').removeClass('sight-portfolio-loading'); // remove loader
                if(data.total_post != "" && data.total_post > data.posts_per_page && data.posts_per_page > 0){
                    loadMoreButton.show(); // if last page, HIDE the button
                }
                else{
                    loadMoreButton.hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                }); 
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;
    });

    // Load course library on click of load more button
    jQuery('.course1-load-btn').on('click' , function(){
        courseListFilter(this);
    });
    var course_pagenumber = 1;
    function courseListFilter(obj){
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination_course');
        let loadMoreText = jQuery(obj).text();
        let post_per_page = jQuery(obj).attr('data-total-post');
        let category_id = jQuery(obj).attr('data-category-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        if(loadMoreText != ''){
            course_pagenumber++;
        }
        else{
            course_pagenumber = 1;
        }
        jQuery('div.sight-portfolio-area__main_course').addClass('sight-portfolio-loading'); // add loader
        jQuery('.course1-load-btn').text('Loading'); // add loading text
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_course', 
                post_per_page : post_per_page,
                category_id : category_id,
                pageNumber : course_pagenumber,
                load_more_post : "load_more_post",
            },
            dataType: "json",
            success: function(data){
                jQuery('div.sight-portfolio-area__main_course').removeClass('sight-portfolio-loading'); // remove loader
                jQuery('.course1-load-btn').text('Load More'); // add load more text
                if(loadMoreText != ''){
                    jQuery(".sight-portfolio-area__main_course").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                }
                else{
                    jQuery(".sight-portfolio-area__main_course").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                    else{
                        loadMoreButton.show();
                    } 
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;　
    }

    // Load course library on click of sub-category list
    jQuery('.courses1-category-list').on('click', function(e){
        e.preventDefault();
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination_course');
        let loadMoreText = jQuery('.course1-load-btn').text();
        let post_per_page = jQuery('.course1-load-btn').attr('data-total-post');
        let list_category_id = jQuery(this).attr('data-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        course_pagenumber = 1;
        jQuery('.course1-load-btn').attr('data-category-id', list_category_id); // Change category-id of load more button
        jQuery('div.sight-portfolio-area__main_course').addClass('sight-portfolio-loading'); // add loader
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_course', 
                post_per_page : post_per_page,
                category_id : list_category_id,
                pageNumber : course_pagenumber,
                load_more_post : "load_category_post",
            },
            dataType: "json",
            success: function(data){
                jQuery(".sight-portfolio-area__main_course").html(data.content);
                jQuery('div.sight-portfolio-area__main_course').removeClass('sight-portfolio-loading'); // remove loader
                if(data.total_post != "" && data.total_post > data.posts_per_page && data.posts_per_page > 0){
                    loadMoreButton.show(); // if last page, HIDE the button
                }
                else{
                    loadMoreButton.hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                }); 
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;
    });

    // Load affiliates course on click of load more button
    jQuery('.course2-load-btn').on('click' , function(){
        affiliatesListFilter(this);
    });
    var affiliates_pagenumber = 1;
    function affiliatesListFilter(obj){
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination_affiliates');
        let loadMoreText = jQuery(obj).text();
        let post_per_page = jQuery(obj).attr('data-total-post');
        let category_id = jQuery(obj).attr('data-category-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        if(loadMoreText != ''){
            affiliates_pagenumber++;
        }
        else{
            affiliates_pagenumber = 1;
        }
        jQuery('div.sight-portfolio-area__main_affiliates_course').addClass('sight-portfolio-loading'); // add loader
        jQuery('.course2-load-btn').text('Loading'); // add loading text
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_affiliates_course', 
                post_per_page : post_per_page,
                category_id : category_id,
                pageNumber : affiliates_pagenumber,
                load_more_post : "load_more_post",
            },
            dataType: "json",
            success: function(data){
                jQuery('div.sight-portfolio-area__main_affiliates_course').removeClass('sight-portfolio-loading'); // remove loader
                jQuery('.course2-load-btn').text('Load More'); // add load more text
                if(loadMoreText != ''){
                    jQuery(".sight-portfolio-area__main_affiliates_course").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                }
                else{
                    jQuery(".sight-portfolio-area__main_affiliates_course").append(data.content);
                    if(data.page == data.max_pages){
                        loadMoreButton.hide(); // if last page, HIDE the button
                    } 
                    else{
                        loadMoreButton.show();
                    } 
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;　
    }

    // Load affiliates course on click of sub-category list
    jQuery('.courses2-category-list').on('click', function(e){
        e.preventDefault();
        let loadMoreButton = jQuery('.sight-portfolio-area__pagination_affiliates');
        let loadMoreText = jQuery('.course2-load-btn').text();
        let post_per_page = jQuery('.course2-load-btn').attr('data-total-post');
        let list_category_id = jQuery(this).attr('data-id');
        let post_admin_URL = csco_post_list.ajaxurl;
        affiliates_pagenumber = 1;
        jQuery('.course2-load-btn').attr('data-category-id', list_category_id); // Change category-id of load more button
        jQuery('div.sight-portfolio-area__main_affiliates_course').addClass('sight-portfolio-loading'); // add loader
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: { 
                action : 'get_more_affiliates_course', 
                post_per_page : post_per_page,
                category_id : list_category_id,
                pageNumber : affiliates_pagenumber,
                load_more_post : "load_category_post",
            },
            dataType: "json",
            success: function(data){
                jQuery(".sight-portfolio-area__main_affiliates_course").html(data.content);
                jQuery('div.sight-portfolio-area__main_affiliates_course').removeClass('sight-portfolio-loading'); // remove loader
                if(data.total_post != "" && data.total_post > data.posts_per_page && data.posts_per_page > 0){
                    loadMoreButton.show(); // if last page, HIDE the button
                }
                else{
                    loadMoreButton.hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                }); 
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
        return false;
    });

    // Mini post category
    jQuery('.mini-post-category .pk-featured-image').addClass('jarallax-keep-img'); // add class
    jQuery('.mini-post-category .pk-featured-image').attr('data-slide', '0.7'); // add atribute
    jQuery('.mini-post-category .pk-featured-image img').addClass('jarallax-img'); // add claas in img
    
    objectFitImages();
    /* init Jarallax */
    jarallax(document.querySelectorAll('.jarallax'));
    jarallax(document.querySelectorAll('.jarallax-keep-img'), {
       keepImg: true,
    });

    // Load FAQ on click of category
    jQuery(document).on('change','.filter-size',function(e){
        e.preventDefault();
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;
        let faq_search = jQuery('.faq_serach').val();
        let post_admin_URL = csco_post_list.ajaxurl;
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: {
                 action : 'size_ajax_action', 
                 size : size,
                 page : page,
                 ppp : ppp,
                 category_post : 'category_post',
                 search_keyword : faq_search,
                 faq_search: "faq_search",
            },
            dataType: "json",
            success:function(data) {
                if(data.status == 1){
                    jQuery('#datafetch').html(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else if(data.status == 2){
                    jQuery('#datafetch').append(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else{
                    jQuery('#datafetch').html(data.content); 
                    jQuery(".casemorebtn").hide();
                }
                if(data.page > data.max_pages){
                    jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                }

                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
             console.log(errorThrown);
            }
        });
    });

    // FAQ list load more pagination
    jQuery(document).on('click','.faq-pagination', function(e){
        e.preventDefault();
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;
        page++;
        let faq_search = jQuery('.faq_serach').val();
        let post_admin_URL = csco_post_list.ajaxurl;
        jQuery('.faq-pagination').text('Loading'); // add loading text
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: {
                action : 'size_ajax_action', 
                size : size,
                page : page,
                ppp: ppp,
                search_keyword : faq_search,
                faq_search: "faq_search",
            },
            dataType: "json",
            success:function(data){
                jQuery('.faq-pagination').text('Load More'); // add loading text
                if(data.status == 1){
                    jQuery('#datafetch').append(data.content);
                    jQuery(".casemorebtn").show();
                }
                else if(data.status == 2){
                    jQuery('#datafetch').append(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else{
                    jQuery('#datafetch').append(data.content); 
                    jQuery(".casemorebtn").hide();
                }
                if(data.page > data.max_pages){
                    jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
                console.log(errorThrown);
            }
        });
    });

    // Load FAQ on click of search button
    jQuery("#faqBtnSearch").on('click', function(e){
        e.preventDefault();
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;
        let faq_search = jQuery('.faq_serach').val();
        if(faq_search && faq_search !== "" && faq_search !== "undefined"){
            let post_admin_URL = csco_post_list.ajaxurl;
            jQuery.ajax({
                url: post_admin_URL,
                type: "POST",
                data: {
                    action : 'size_ajax_action', 
                    size : size,
                    page : page,
                    ppp: ppp,
                    search_keyword : faq_search,
                    faq_search: "faq_search",
                },
                dataType: "json",
                success:function(data){
                    if(data.status == 1){
                        jQuery('#datafetch').html(data.content);
                        jQuery(".casemorebtn").show();
                    }
                    else if(data.status == 2){
                        jQuery('#datafetch').append(data.content);
                        jQuery(".casemorebtn").show();
                    } 
                    else{
                        jQuery('#datafetch').html(data.content); 
                        jQuery(".casemorebtn").hide();
                    }
                    if(data.page > data.max_pages){
                        jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                    }
                    /* init Jarallax */
                    jarallax(document.querySelectorAll('.jarallax'));
                    jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                        keepImg: true,
                    });
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
        else{
            alert("Please enter search title");
        }
    });

    // Load FAQ post on keyup (When empty)
    jQuery('.faq_serach').on('keyup', function(e){
        e.preventDefault();
        jQuery("#cleanSearch").show();
        let faq_search = jQuery(this).val();
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;

        if(faq_search && faq_search !== "" && faq_search !== "undefined"){
            let post_admin_URL = csco_post_list.ajaxurl;
            jQuery.ajax({
                url: post_admin_URL,
                type: "POST",
                data: {
                    action : 'size_ajax_action', 
                    size : size,
                    page : page,
                    ppp: ppp,
                    search_keyword : faq_search,
                    faq_search: "faq_onkeyup_search",
                },
                dataType: "json",
                success:function(data){
                    jQuery('#faq_result').html(data.content);   
                    /* init Jarallax */
                    jarallax(document.querySelectorAll('.jarallax'));
                    jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                        keepImg: true,
                    });
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
        else{
            page = 1;
            var arr_size = [];
            jQuery(".filter-size").each(function(){
                if(jQuery(this).is(":checked")){
                    arr_size.push(jQuery(this).val());
                }
            });
            var size = arr_size.join(",");
            var ppp = 6;
            let post_admin_URL = csco_post_list.ajaxurl;
            jQuery.ajax({
                url: post_admin_URL,
                type: "POST",
                data: {
                     action : 'size_ajax_action', 
                     size : size,
                     page : page,
                     ppp : ppp,
                     category_post : 'category_post',
                },
                dataType: "json",
                success:function(data) {
                    if(data.status == 1){
                        jQuery('#datafetch').html(data.content);
                        jQuery(".casemorebtn").show();
                    } 
                    else if(data.status == 2){
                        jQuery('#datafetch').append(data.content);
                        jQuery(".casemorebtn").show();
                    } 
                    else{
                        jQuery('#datafetch').append(data.content); 
                        jQuery(".casemorebtn").hide();
                    }
                    if(data.page > data.max_pages){
                        jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                    }
                    /* init Jarallax */
                    jarallax(document.querySelectorAll('.jarallax'));
                    jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                        keepImg: true,
                    });
                },
                error: function(errorThrown){
                 console.log(errorThrown);
                }
            });
            jQuery("#cleanSearch").hide();
        }
    });

    // Load FAQ post on click of close search button
    jQuery("#cleanSearch").on('click', function(){
        jQuery('.faq_serach').val('');  
        jQuery(this).hide();
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;
        let post_admin_URL = csco_post_list.ajaxurl;
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: {
                 action : 'size_ajax_action', 
                 size : size,
                 page : page,
                 ppp : ppp,
                 category_post : 'category_post',
            },
            dataType: "json",
            success:function(data) {
                if(data.status == 1){
                    jQuery('#datafetch').html(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else if(data.status == 2){
                    jQuery('#datafetch').append(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else{
                    jQuery('#datafetch').append(data.content); 
                    jQuery(".casemorebtn").hide();
                }
                if(data.page > data.max_pages){
                    jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
             console.log(errorThrown);
            }
        });
    });

    // Reset category and load FAQ post on click of reset button
    jQuery("#resetCat").on('click', function(){
        let faq_search = jQuery('.faq_serach').val();
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            jQuery(this).prop("checked", false);
        });
        var size = arr_size.join(",");
        var ppp = 6;
        let post_admin_URL = csco_post_list.ajaxurl;
        jQuery.ajax({
            url: post_admin_URL,
            type: "POST",
            data: {
                 action : 'size_ajax_action', 
                 size : size,
                 page : page,
                 ppp : ppp,
                 category_post : 'category_post',
                 search_keyword : faq_search,
                 faq_search: "faq_search",
            },
            dataType: "json",
            success:function(data) {
                if(data.status == 1){
                    jQuery('#datafetch').html(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else if(data.status == 2){
                    jQuery('#datafetch').append(data.content);
                    jQuery(".casemorebtn").show();
                } 
                else{
                    jQuery('#datafetch').append(data.content); 
                    jQuery(".casemorebtn").hide();
                }
                if(data.page > data.max_pages){
                    jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                }
                /* init Jarallax */
                jarallax(document.querySelectorAll('.jarallax'));
                jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                    keepImg: true,
                });
            },
            error: function(errorThrown){
             console.log(errorThrown);
            }
        });
    });

    // Display FAQ's post on click of search result list
    jQuery(document).on('click', '.search_result', function(e){
        e.preventDefault();
        jQuery(".search_result").hide();
        let search_value = jQuery(this).text();
        jQuery('.faq_serach').val(search_value); 
        let faq_search = search_value;
        
        page = 1;
        var arr_size = [];
        jQuery(".filter-size").each(function(){
            if(jQuery(this).is(":checked")){
                arr_size.push(jQuery(this).val());
            }
        });
        var size = arr_size.join(",");
        var ppp = 6;
        if(faq_search && faq_search !== "" && faq_search !== "undefined"){
            let post_admin_URL = csco_post_list.ajaxurl;
            jQuery.ajax({
                url: post_admin_URL,
                type: "POST",
                data: {
                    action : 'size_ajax_action', 
                    size : size,
                    page : page,
                    ppp: ppp,
                    search_keyword : faq_search,
                    faq_search: "faq_search",
                },
                dataType: "json",
                success:function(data){
                    if(data.status == 1){
                        jQuery('#datafetch').html(data.content);
                        jQuery(".casemorebtn").show();
                    }
                    else if(data.status == 2){
                        jQuery('#datafetch').append(data.content);
                        jQuery(".casemorebtn").show();
                    } 
                    else{
                        jQuery('#datafetch').html(data.content); 
                        jQuery(".casemorebtn").hide();
                    }
                    if(data.page > data.max_pages){
                        jQuery('.casemorebtn').hide(); // if last page, HIDE the button
                    }
                    /* init Jarallax */
                    jarallax(document.querySelectorAll('.jarallax'));
                    jarallax(document.querySelectorAll('.jarallax-keep-img'), {
                        keepImg: true,
                    });
                },
                error: function(errorThrown){
                    console.log(errorThrown);
                }
            });
        }
        else{
            alert("Please enter search title");
        }
    });

});