// WordPress主题的JavaScript入口文件
console.log('🎉 Xinyun Theme with Vite & Tailwind CSS v4 loaded successfully!');

// 引入 Splide（本地依赖）
import Splide from '@splidejs/splide';
import '@splidejs/splide/css';

// 检查CSS是否正确加载
const commentsArea = document.querySelector('.comments-area');
if (commentsArea) {
  console.log('✅ Comments area found, Tailwind styles should be applied');
  
  // 检查测试按钮
  const testButtons = document.querySelectorAll('.bg-blue-500, .bg-green-500, .bg-red-500');
  if (testButtons.length > 0) {
    console.log(`✅ Found ${testButtons.length} test buttons with Tailwind classes`);
    
    // 检查样式是否真正应用
    const blueButton = document.querySelector('.bg-blue-500');
    if (blueButton) {
      const styles = window.getComputedStyle(blueButton);
      console.log('🎨 Blue button background color:', styles.backgroundColor);
      console.log('🎨 Blue button border radius:', styles.borderRadius);
    }
  } else {
    console.log('❌ No test buttons found');
  }
} else {
  console.log('❌ Comments area not found');
}

// 评论组件的交互逻辑
document.addEventListener('DOMContentLoaded', function() {
  // 统一初始化：所有标记为 data-carousel="splide" 的容器
  const containers = document.querySelectorAll('[data-carousel="splide"]');
  containers.forEach((container) => {
    const el = container.querySelector('.splide');
    if (!el) return;

    const type = container.getAttribute('data-type') || 'loop';
    const autoplay = container.getAttribute('data-autoplay') !== 'false';
    const interval = parseInt(container.getAttribute('data-interval') || '5000', 10);
    const arrows = container.getAttribute('data-arrows') !== 'false';
    const pagination = container.getAttribute('data-pagination') !== 'false';
    const height = container.getAttribute('data-height') || '400px';
    const mobileHeight = container.getAttribute('data-mobile-height') || '300px';
    const lazyLoad = container.getAttribute('data-lazy') || 'nearby';

    try {
      new Splide(el, {
        type,
        autoplay,
        interval,
        pauseOnHover: true,
        pauseOnFocus: true,
        resetProgress: false,
        height,
        cover: true,
        arrows,
        pagination,
        lazyLoad,
        breakpoints: { 768: { height: mobileHeight, arrows: false } },
      }).mount();
    } catch (e) {
      console.error('Splide init failed:', e);
    }
  });

  // 评论表单增强
  const commentForm = document.getElementById('commentform');
  if (commentForm) {
    // 添加表单验证或其他交互逻辑
    console.log('Comment form found and enhanced');
  }
  
  // 回复按钮交互
  const replyLinks = document.querySelectorAll('.comment-reply-link');
  replyLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      console.log('Reply button clicked');
    });
  });
});
