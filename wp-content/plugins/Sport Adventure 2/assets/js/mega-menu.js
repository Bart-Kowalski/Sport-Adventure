document.addEventListener('DOMContentLoaded', function() {
  const tripLinks = document.querySelectorAll('.megamenu-7__tax-link');
  let currentImageSrc = '';
  let changeTimeout = null;
  let imageCache = new Map(); // Cache for preloaded images
  
  function getCurrentTabImage() {
    const activeTab = document.querySelector('.megamenu-7__tab-panel.brx-open');
    if (!activeTab) return null;
    return activeTab.querySelector('.card-megamenu-7__img img');
  }
  
  // Add CSS for transition
  const style = document.createElement('style');
  style.textContent = `
    .card-megamenu-7__img img {
      transition: opacity 0.3s ease-in-out;
    }
    .card-megamenu-7__img img.changing {
      opacity: 0;
    }
  `;
  document.head.appendChild(style);
  
  // Preload image
  function preloadImage(src) {
    if (imageCache.has(src)) return imageCache.get(src);
    
    const promise = new Promise((resolve, reject) => {
      const img = new Image();
      img.onload = () => resolve(src);
      img.onerror = reject;
      img.src = src;
    });
    
    imageCache.set(src, promise);
    return promise;
  }
  
  function updateImage(newSrc) {
    const currentImage = getCurrentTabImage();
    if (!currentImage) return;
    
    // Get base URL without dimensions
    const baseUrl = newSrc.replace(/-\d+x\d+(?=\.[a-z]+$)/i, '');
    
    // Skip if it's the same image
    if (baseUrl === currentImageSrc) return;
    
    // Clear any pending timeouts
    if (changeTimeout) {
      clearTimeout(changeTimeout);
    }
    
    // Preload the image before showing
    preloadImage(baseUrl).then(() => {
      currentImageSrc = baseUrl;
      currentImage.classList.add('changing');
      
      changeTimeout = setTimeout(() => {
        currentImage.src = baseUrl;
        if (currentImage.hasAttribute('data-src')) {
          currentImage.setAttribute('data-src', baseUrl);
        }
        currentImage.classList.remove('perfmatters-lazy', 'entered', 'loading', 'pmloaded');
        currentImage.classList.remove('changing');
      }, 300);
    });
  }
  
  // Preload images for visible tab
  function preloadTabImages(tabPanel) {
    const links = tabPanel.querySelectorAll('.megamenu-7__tax-link');
    links.forEach(link => {
      const productImageHTML = link.getAttribute('data-product-image');
      if (productImageHTML) {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = productImageHTML;
        const productImageSrc = tempDiv.querySelector('img')?.src;
        if (productImageSrc) {
          preloadImage(productImageSrc.replace(/-\d+x\d+(?=\.[a-z]+$)/i, ''));
        }
      }
    });
  }
  
  // Handle tab changes
  document.querySelectorAll('.megamenu-7__tab-title').forEach(tab => {
    tab.addEventListener('click', function() {
      const tabId = this.getAttribute('aria-controls');
      const tabPanel = document.getElementById(tabId);
      if (tabPanel) {
        setTimeout(() => preloadTabImages(tabPanel), 100);
      }
    });
  });
  
  tripLinks.forEach(link => {
    link.addEventListener('mouseenter', function() {
      const productImageHTML = this.getAttribute('data-product-image');
      if (!productImageHTML) return;
      
      const tempDiv = document.createElement('div');
      tempDiv.innerHTML = productImageHTML;
      const productImageSrc = tempDiv.querySelector('img')?.src;
      
      if (productImageSrc) {
        updateImage(productImageSrc);
      }
    });
  });
  
  // Initialize currentImageSrc with the default image
  const defaultImage = getCurrentTabImage();
  if (defaultImage) {
    currentImageSrc = defaultImage.src.replace(/-\d+x\d+(?=\.[a-z]+$)/i, '');
  }
  
  // Preload images for initial active tab
  const activeTab = document.querySelector('.megamenu-7__tab-panel.brx-open');
  if (activeTab) {
    preloadTabImages(activeTab);
  }
}); 