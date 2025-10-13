function xFavorite() {

    let x_favorite_time = document.cookie.split('; ').find(row => row.startsWith('x_favorite_time='))?.split('=')[1];
    const now = Math.floor(Date.now() / 1000);
    const lastChecked = x_favorite_time || 0;
    const seconds = 5 * 60; /* no more than every 5 mins */
    const timeToCheck =  (now - lastChecked ) > seconds
    const loggedInCookie = document.cookie.split('; ').find(row => row.startsWith('x_logged_in_user='));
    const userLoggedIn = loggedInCookie != null && document.body.classList.contains('logged-in')

    if (timeToCheck && !userLoggedIn) {

        const favorite_nonce = xFavoriteObject.sec;
        const ajaxurl = xFavoriteObject.ajaxurl;

        const validateIDsData = {
            action: 'x_validate_favorite_ids',
            sec: favorite_nonce,
        };
        
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams(validateIDsData).toString(),
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            }
        })
        .catch(error => {
            console.error('Error updating cookie:', error);
        });

    }

    function fadeOut(element) {

        element.style.height = element.scrollHeight + 'px';
        element.offsetHeight;
        element.classList.add('x-favorite_fade-out');

        element.addEventListener('transitionend', function() {
            element.remove();
        }, { once: true }); 
        
    }

    function triggerButtonsEvents(data, postType, postId, favorite, buttonType, maximumReached = false) {

            // Check if the post type exists in data
            if (data.data[postType]) {

                const postIds = data.data[postType];
                

                if (postIds.includes(postId)) {
                    favorite.dispatchEvent(new CustomEvent("x_favorite:added", {
                        "detail": {
                            "data" : data.data,
                            "postType": postType,
                            "postId": postId, 
                        }
                      }))
                } else {
                if (postIds.length > 0) {
                    favorite.dispatchEvent(new Event('x_favorite:removed', {
                        "detail": {
                            "postType": postType,
                            "postId": postId, 
                        }
                      }))
                } else {
                    if ( 'clear' === buttonType ) {
                        favorite.dispatchEvent(new CustomEvent("x_favorite:cleared", {
                            "detail": {
                                "postType": postType, 
                            }
                          }))
                    } else {
                        favorite.dispatchEvent(new Event('x_favorite:removed', {
                            "detail": {
                                "postType": postType,
                                "postId": postId, 
                            }
                          }))
                    }
                }
            }
            } else {
                favorite.dispatchEvent(new CustomEvent("x_favorite:cleared", {
                    "detail": {
                        "postType": postType, 
                    }
                  }))
            }

            if (data.message || maximumReached) {
                favorite.dispatchEvent(new CustomEvent("x_favorite:maximum-reached", {
                    "detail": {
                        "postType": postType, 
                    }
                  }))
            }

            window.dispatchEvent(new CustomEvent("x_favorite:updated", {
                "detail": {
                    "data" : data.data,
                    "postType": postType, 
                }
              }))
        

    }

    // Function to get the value of a cookie by name
    function xGetCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) {
            return decodeURIComponent( parts.pop().split(';').shift() );
        } 
        return null;

    }

    function xDisAllowFavorite() {

        if ( document.body.classList.contains('logged-in') ) {
            return;
        }
    
        document.cookie = "x_favorite_allow=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    
        const favorite_nonce = xFavoriteObject.sec;
        const ajaxurl = xFavoriteObject.ajaxurl;
    
        const newFavoriteData = {
            action: 'x_remove_favorite',
            sec: favorite_nonce,
        };
    
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams(newFavoriteData).toString(),
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.dispatchEvent(new CustomEvent("x_favorite:disallowed"))
                document.querySelectorAll('.x-favorite').forEach(favorite => {
                        favorite.setAttribute('disabled','')
                        favorite.classList.remove('x-favorite_added')
                })
            }
        })
        .catch(error => {
            console.error('Error updating cookie:', error);
        });

        
        document.querySelectorAll('[data-x-favorite-count').forEach(count => {
            count.innerHTML = '0'
        })

        document.querySelectorAll('.x-favorite_count').forEach(count => {
            count.classList.add('x-favorite_count-zero')
            if ( count.querySelector('.x-favorite_count-inner') ) {
                count.querySelector('.x-favorite_count-inner').innerHTML = '0'
            }
        })
    
    }

    function xSetCookie(name, value, days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "expires=" + date.toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; ${expires}; secure; SameSite=Strict; path=/`;
    }
    
    function xAllowFavorite(daysToExpire = 7) {
    
            if ( document.body.classList.contains('logged-in') ) {
                return;
            }
    
            const cookieName = 'x_favorite_allow';
            const cookieValue = 'true';
        
            // Check if the cookie already exists
            if (!xGetCookie(cookieName)) {
                
                // Set the cookie for [daysToExpire] days
                xSetCookie(cookieName, cookieValue, daysToExpire);
        
            } 
    
            document.querySelectorAll('.brxe-xfavorite').forEach(favorite => {
                if ( favorite.querySelector('.x-favorite') ) {
                    favorite.querySelector('.x-favorite').removeAttribute('disabled')
                }
            })
    
            window.dispatchEvent(new CustomEvent("x_favorite:allowed"))
        
    }

    function xMaybeAllowedFavorite(container) {

        const cookieName = 'x_favorite_allow';
    
        // Check if the cookie already exists
        if ( xGetCookie(cookieName) ) {
    
            container.querySelectorAll('.brxe-xfavorite').forEach(favorite => {
                if ( favorite.querySelector('.x-favorite') ) {
                    favorite.querySelector('.x-favorite').removeAttribute('disabled')
                }
            })
    
        }
    
    }

    function syncButtonsWithCookie(favoriteCookieObj, favorite, buttonType, favoriteButton, postType, postId) {

        if ( favoriteCookieObj ) {

            if ( buttonType === 'add_remove' && favoriteButton ) {
                if ( favoriteCookieObj[postType] && favoriteCookieObj[postType].includes(postId) ) {
                    favoriteButton.classList.add('x-favorite_added')
                    favoriteButton.setAttribute('aria-pressed', 'true');
                } else {
                    favoriteButton.classList.remove('x-favorite_added')
                    favoriteButton.setAttribute('aria-pressed', 'false');
                }
            } else if ( buttonType === 'count' && favorite.querySelector('.x-favorite_count-inner') ) {
                if ( favoriteCookieObj[postType] && favoriteCookieObj[postType].length > 0 ) {
                    favorite.querySelector('.x-favorite_count-inner').innerHTML = favoriteCookieObj[postType].length.toString()
                    favorite.querySelector('.x-favorite_count').classList.remove('x-favorite_count-zero')
                } else {
                    favorite.querySelector('.x-favorite_count-inner').innerHTML = '0'
                }
            }

            if ( document.querySelector('[data-x-favorite-count="' + postType + '"]') ) {
                document.querySelectorAll('[data-x-favorite-count="' + postType + '"]').forEach(count => {
                    if ( favoriteCookieObj[postType] && favoriteCookieObj[postType].length > 0 ) {
                        count.innerHTML = favoriteCookieObj[postType].length
                    } else {
                        count.innerHTML = '0'
                    }
                })
            }

        }
    
    }

    const extrasFavorite = function ( container, ajax = false ) {

        const favorite_nonce = xFavoriteObject.sec;
        const ajaxurl = xFavoriteObject.ajaxurl;
        const cookieName = 'x_favorite_ids';

        const loggedInCookie = document.cookie.split('; ').find(row => row.startsWith('x_logged_in_user='));
        const userLoggedIn = !!loggedInCookie && document.body.classList.contains('logged-in')

        const favoriteIds = {};

        // Loop through all cookies
        document.cookie.split("; ").forEach(cookie => {
            // Match cookies that start with "x_favorite_ids__"
            const [name, value] = cookie.split("=");
            if (name.startsWith("x_favorite_ids__")) {
            const key = name.replace("x_favorite_ids__", ""); // e.g., "post" from "x_favorite_ids__post"
            favoriteIds[key] = JSON.parse(decodeURIComponent(value) || "[]"); // Parse IDs array
            }
        });
        
        let favoriteCookieObj = favoriteIds;

          let favorites = container.querySelectorAll('.brxe-xfavorite');

          favorites.forEach(favorite => {

            const configAttr = favorite.getAttribute('data-x-favorite')
            const config = configAttr ? JSON.parse(configAttr) : {}
            const buttonType = favorite.getAttribute('data-x-type')
            const list = favorite.getAttribute('data-x-list');
            const listMaximum = favorite.hasAttribute('data-x-list-max') ? parseInt( favorite.getAttribute('data-x-list-max') ) : 0;

            let postId = parseInt( favorite.getAttribute('data-post-id') );
            
            if ( favorite.hasAttribute('data-x-item-id') ) {
                postId = parseInt( favorite.getAttribute('data-x-item-id') )
            }

            const favoriteButton = favorite.querySelector('button')

            if ( favoriteCookieObj ) {
                syncButtonsWithCookie(favoriteCookieObj,favorite, buttonType, favoriteButton, list, postId);
            }

            favorite.addEventListener('click', (e) => {

                if ( favorite.querySelector('.x-favorite').hasAttribute('disabled') ) {
                    e.preventDefault()
                }

            })

            if ( favoriteButton && !favoriteButton.hasAttribute('data-x-click' ) ) {

                favorite.addEventListener('click', (e) => {

                    e.preventDefault()

                    favoriteButton.classList.add('x-favorite_busy')

                    let maximumReached = false;
                    let setMaximum = false;

                    if ( userLoggedIn ) {

                        /* manage everything within the meta data */

                        const newFavoriteData = {
                            action: 'x_update_favorite',
                            post_id: postId,
                            post_type: list,
                            listMaximum: listMaximum,
                            cookie: cookieName + '__' + list,
                            sec: favorite_nonce,
                            type: buttonType
                        };
    
                        fetch(ajaxurl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                            },
                            body: new URLSearchParams(newFavoriteData).toString(),
                            credentials: 'same-origin'
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                triggerButtonsEvents(data.data, list, postId, favorite, buttonType);
                                if ( 'remove' !== buttonType ) {
                                    favoriteButton.classList.remove('x-favorite_busy')
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error updating cookie:', error);
                        });

                    }  else {

                        /* manage everything in the browser */

                                let cookieData = { "data": {} };
                                let cookiePrefix = 'x_favorite_ids__';
                                let allCookies = document.cookie.split('; ');

                                // Loop through all cookies and aggregate data for the relevant cookies
                                allCookies.forEach(cookie => {
                                    let [key, value] = cookie.split('=');
                                    if (key.startsWith(cookiePrefix)) {
                                        let parsedValue = JSON.parse(decodeURIComponent(value));

                                        cookieData.data[key.replace(cookiePrefix, '')] = parsedValue; // Store postType key

                                        // Ensure parsedValue is an object and not an array
                                        if (typeof parsedValue === 'object' && !Array.isArray(parsedValue)) {
                                            Object.keys(parsedValue).forEach(postType => {
                                                if (!cookieData.data[postType]) {
                                                    cookieData.data[postType] = []; // Initialize an array for this postType if it doesn't exist
                                                }
                                                // Merge unique values from parsedValue into the correct postType array
                                                parsedValue[postType].forEach(id => {
                                                    if (!cookieData.data[postType].includes(id)) {
                                                        cookieData.data[postType].push(id);
                                                    }
                                                });
                                            });
                                        }
                                    }
                                });

                                // Now, handle the button type actions
                                if ('clear' === buttonType) {
                                    // Clear all IDs for the given list
                                    if (cookieData.data[list]) {
                                        cookieData.data[list] = [];
                                    }
                                } else {
                                    // Ensure the list exists in cookieData
                                    if (!cookieData.data[list]) {
                                        cookieData.data[list] = [];
                                    }

                                    // Check if the postId already exists in the list array
                                    let index = cookieData.data[list].indexOf(postId);

                                    if (index !== -1) {
                                        // If it exists, remove it
                                        cookieData.data[list].splice(index, 1);
                                    } else {
                                        // If it doesn't exist, check the maximum list size
                                        if (listMaximum >= 1) {
                                            setMaximum = true;
                                        }

                                        if (!setMaximum || cookieData.data[list].length < listMaximum) {
                                            // Push postId to the respective list (postType)
                                            if (!cookieData.data[list].includes(postId)) { // Ensure no duplicates
                                                cookieData.data[list].push(postId);
                                            }
                                            maximumReached = false;
                                        } else {
                                            maximumReached = true;
                                        }
                                    }
                                }

                                // Update cookies with new cookieData
                                Object.keys(cookieData.data).forEach(lst => {
                                    if (cookieData.data[lst].length > 0) {
                                        // Only update the cookie if there is data for that list
                                        document.cookie = `${cookiePrefix}${lst}=${encodeURIComponent(JSON.stringify(cookieData.data[lst]))};` +
                                                            ` path=/;` +
                                                            ` max-age=604800;` + // 7 days in seconds
                                                            ` secure;` +         // Sets the Secure attribute
                                                            ` samesite=Strict;`;
                                    } else {
                                        // If the array is empty, remove the cookie
                                        document.cookie = `${cookiePrefix}${lst}=; path=/; max-age=0;`; // Set to expire immediately
                                    }
                                });

                                const daysToExpire = 7; // Default expiration

                                 x_favorite_time = document.cookie.split('; ').find(row => row.startsWith('x_favorite_time='))?.split('=')[1];

                                 if ( !x_favorite_time ) {
                                    xSetCookie('x_favorite_time', Math.floor(Date.now() / 1000), daysToExpire);
                                 }
                            
                                // Check if the cookie already exists
                                if (!xGetCookie(cookieName)) {
                                    
                                    // Set the cookie for allowing cookies
                                    xSetCookie('x_favorite_allow', 'true', daysToExpire);
                            
                                } else {
                                    
                                }


                        setTimeout(() => {

                            triggerButtonsEvents(cookieData, list, postId, favorite, buttonType, maximumReached);

                            if ( 'remove' !== buttonType ) {
                                favoriteButton.classList.remove('x-favorite_busy')
                            }
                          }, 200);

                    }
    
                       
                })

                if ( !favoriteButton.hasAttribute('data-x-click' ) ) {
                    favoriteButton.setAttribute('data-x-click', '' )
                }

                favorite.addEventListener('x_favorite:cleared', (e) => { 

                    if ( config.clearedText && favorite.querySelector('.x-favorite_text-inner') ) {
                        favorite.querySelector('.x-favorite_text-inner').innerHTML = config.clearedText
                    }

                    favoriteButton.setAttribute('aria-pressed', 'true');

                    if ( 'add_remove' !== buttonType ) {

                        if ( 'clear' === buttonType ) {

                            document.querySelectorAll('[data-x-favorite-item="' + e.detail.postType + '"]').forEach(item => {
                                fadeOut(item)
                            })

                            document.querySelectorAll('.brxe-xfavorite[data-x-list="' + e.detail.postType + '"]').forEach(item => {
                                item.dispatchEvent(new Event('x_favorite:removed'))
                            })

                            favoriteButton.classList.add('x-favorite_cleared')
                            favoriteButton.setAttribute('aria-pressed','true');
                            

                        } else {
                            
                            if ( null != config.isLooping ) {
                                let favoriteItem = favorite.closest('.brxe-' + config.isLooping)
                                if (favoriteItem) { 
                                    fadeOut(favoriteItem)
                                }
                            } 

                        }

                    } else {

                        favoriteButton.classList.remove('x-favorite_added')
                        favoriteButton.setAttribute('aria-pressed', 'false');
                        if ( config.addText && favorite.querySelector('.x-favorite_text-inner') ) {
                            favorite.querySelector('.x-favorite_text-inner').innerHTML = config.addText
                        }

                    }

                })

                favorite.addEventListener('x_favorite:added', (e) => { 

                    container.querySelectorAll('.brxe-xfavorite[data-x-list="' + list + '"][data-x-type="add_remove"][data-post-id="' + postId + '"]').forEach(otherFavorite => {

                        if ( otherFavorite !== favorite ) {

                            otherFavorite.dispatchEvent(new CustomEvent("x_favorite:also-added", {
                                "detail": {
                                    "postType": list,
                                    "postId": postId, 
                                }
                            }))

                        }

                    })

                    window.dispatchEvent(new CustomEvent("x_favorite:added", {
                        "detail": {
                            "postType": list,
                            "postId"    : postId,
                        }
                      }))

                      favorite.dispatchEvent(new CustomEvent("x_favorite:count-reached_" + e.detail.data[list].length), {
                        "detail": {
                            "data" : e.detail.data,
                            "postType": list
                        }
                      })


                })

                if ('clear' === buttonType) {
                    favorite.addEventListener('x_favorite:not_empty', () => { 
                        favoriteButton.classList.remove('x-favorite_cleared')
                        if ( config.clearText && favorite.querySelector('.x-favorite_text-inner') ) {
                            favorite.querySelector('.x-favorite_text-inner').innerHTML = config.clearText
                        }
                    })
                }

                favorite.addEventListener('x_favorite:removed', () => { 

                    document.querySelectorAll('.brxe-xfavorite[data-x-list="' + list + '"][data-x-type="add_remove"][data-post-id="' + postId + '"]').forEach(otherFavorite => {

                        if ( otherFavorite !== favorite ) {

                            otherFavorite.dispatchEvent(new CustomEvent("x_favorite:also-removed", {
                                "detail": {
                                    "postType": list,
                                    "postId": postId, 
                                }
                            }))

                        }

                    })
    
                    if ( 'remove' === buttonType ) {
                        if ( null != config.isLooping ) {
                            let favoriteItem = favorite.closest('.brxe-' + config.isLooping)
                            if (favoriteItem) { 
                                fadeOut(favoriteItem)
                            }
                         } 
                    } else {
                        favoriteButton.classList.remove('x-favorite_added')
                    }

                    window.dispatchEvent(new CustomEvent("x_favorite:removed", {
                        "detail": {
                            "postType": list,
                            "postId"    : postId,
                        }
                      }))


                })

                favorite.addEventListener('x_favorite:maximum-reached', () => { 

                    favoriteButton.setAttribute('aria-pressed', 'false');
                    
                    if ( config.maxReachedText && favorite.querySelector('.x-favorite_text-inner') ) {
                        favorite.querySelector('.x-favorite_text-inner').innerHTML = config.maxReachedText
                    }

                })

            }

        })

        window.addEventListener("x_favorite:updated", (e) => {

                const postType = e.detail.postType
                const data = e.detail.data

                if (!data[postType]) {
                    data[postType] = [];
                }

                if ( 0 === parseInt( data[postType].length ) ) {
                    document.querySelectorAll('.brxe-xfavorite[data-x-type=clear][data-x-list="' + e.detail.postType + '"]').forEach(clear => {
                        clear.dispatchEvent(new CustomEvent("x_favorite:cleared", {
                            "detail": {
                                "postType": postType, 
                            }
                          }))
                    })
                }

                document.querySelectorAll('.brxe-xfavorite[data-x-type=count]').forEach(counter => {

                    if ( counter.getAttribute('data-x-list') !== e.detail.postType || !counter.querySelector('.x-favorite_count-inner') ) {
                        return;
                    }

                    counter.querySelector('.x-favorite_count-inner').innerHTML = parseInt( data[postType].length )

                    if ( 0 === parseInt( data[postType].length ) ) {
                        counter.querySelector('.x-favorite_count').classList.add('x-favorite_count-zero')
                    } else {
                        counter.querySelector('.x-favorite_count').classList.remove('x-favorite_count-zero')
                    }
                })

                if ( document.querySelector('[data-x-favorite-count="' + postType + '"]') ) {
                    document.querySelectorAll('[data-x-favorite-count="' + postType + '"]').forEach(count => {
                        if ( parseInt( data[postType].length ) > 0 ) {
                            count.innerHTML = parseInt( data[postType].length )
                        } else {
                            count.innerHTML = '0'
                        }
                    })
                }

        })

        window.addEventListener("x_favorite:added", (e) => {

            const postType = e.detail.postType
            const postId = e.detail.postId

            let otherFavoritePostId = ''

            document.querySelectorAll('.brxe-xfavorite[data-x-list="' + postType + '"][data-x-type="add_remove"]').forEach(otherFavorite => {

                if ( otherFavorite.hasAttribute('data-x-item-id') ) {
                    otherFavoritePostId = parseInt( otherFavorite.getAttribute('data-x-item-id') )
                } else {
                    otherFavoritePostId = parseInt( otherFavorite.getAttribute('data-post-id') )
                }

                if ( otherFavoritePostId === postId ) {

                    const configAttr = otherFavorite.getAttribute('data-x-favorite')
                    const config = configAttr ? JSON.parse(configAttr) : {}
                    const otherFavoriteButton = otherFavorite.querySelector('button')

                    otherFavoriteButton.setAttribute('aria-pressed', 'true');
                    otherFavoriteButton.classList.add('x-favorite_added')

                    if ( config.addedText && otherFavoriteButton.querySelector('.x-favorite_text-inner') ) {
                        otherFavoriteButton.querySelector('.x-favorite_text-inner').innerHTML = config.addedText
                    }

                }


            })

        })


        window.addEventListener("x_favorite:removed", (e) => {

            const postType = e.detail.postType
            const postId = e.detail.postId

            let otherFavoritePostId = ''
            
            document.querySelectorAll('.brxe-xfavorite[data-x-list="' + postType + '"][data-x-type="add_remove"]').forEach(otherFavorite => {

                if ( otherFavorite.hasAttribute('data-x-item-id') ) {
                    otherFavoritePostId = parseInt( otherFavorite.getAttribute('data-x-item-id') )
                } else {
                    otherFavoritePostId = parseInt( otherFavorite.getAttribute('data-post-id') )
                }

                if ( otherFavoritePostId === postId ) {

                    const configAttr = otherFavorite.getAttribute('data-x-favorite')
                    const config = configAttr ? JSON.parse(configAttr) : {}
                    const otherFavoriteButton = otherFavorite.querySelector('button')

                    otherFavoriteButton.setAttribute('aria-pressed', 'false');
                    otherFavoriteButton.classList.remove('x-favorite_added')
                        
                    if ( config.addText && otherFavoriteButton.querySelector('.x-favorite_text-inner') ) {
                        otherFavoriteButton.querySelector('.x-favorite_text-inner').innerHTML = config.addText
                    }

                }

            })
        })

    }


   extrasFavorite(document);
   xMaybeAllowedFavorite(document)

  function xFavoriteAJAX(e) {

    if ( typeof e.detail.queryId === 'undefined' ) {
        if ( typeof e.detail.popupElement === 'undefined' ) {
            return;
        } else {
            extrasFavorite( e.detail.popupElement )
        }
    }

    setTimeout(() => {
        if ( document.querySelector('.brxe-' + e.detail.queryId) ) {
            extrasFavorite(document.querySelector('.brxe-' + e.detail.queryId).parentElement);
        }
    }, 0);

  }

  document.addEventListener("bricks/ajax/load_page/completed", xFavoriteAJAX)
  document.addEventListener("bricks/ajax/pagination/completed", xFavoriteAJAX)
  document.addEventListener("bricks/ajax/popup/loaded", xFavoriteAJAX)
  document.addEventListener("bricks/ajax/end", xFavoriteAJAX)

  // Expose function
  window.doExtrasFavorite = extrasFavorite;
  window.xDisAllowFavorite = xDisAllowFavorite;
  window.xAllowFavorite = xAllowFavorite;

}

document.addEventListener("DOMContentLoaded",function(e){
    bricksIsFrontend&&xFavorite()
});