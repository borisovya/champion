import { toRefs, reactive, computed, watch, onMounted } from 'vue'

const layoutConfig = reactive({
  ripple: false,
  darkTheme: localStorage.getItem('theme') === 'dark',
  inputStyle: 'outlined',
  menuMode: 'static',
  theme: localStorage.getItem('theme') === 'dark' ? 'aura-dark-purple' : 'aura-light-purple',
  scale: 14,
  activeMenuItem: null
})
const layoutState = reactive({
  staticMenuDesktopInactive: false,
  overlayMenuActive: false,
  profileSidebarVisible: false,
  configSidebarVisible: false,
  staticMenuMobileActive: false,
  menuHoverActive: false
})

export function useLayout() {
  const changeThemeSettings = (theme, darkTheme) => {
    layoutConfig.darkTheme = darkTheme
    layoutConfig.theme = theme
  }

  const setScale = (scale) => {
    layoutConfig.scale = scale
  }

  const setActiveMenuItem = (item) => {
    layoutConfig.activeMenuItem = item.value || item
  }

  const onMenuToggle = () => {
    if (layoutConfig.menuMode === 'overlay') {
      layoutState.overlayMenuActive = !layoutState.overlayMenuActive
    }

    if (window.innerWidth > 991) {
      layoutState.staticMenuDesktopInactive = !layoutState.staticMenuDesktopInactive
    } else {
      layoutState.staticMenuMobileActive = !layoutState.staticMenuMobileActive
    }
  }

  const isSidebarActive = computed(
    () => layoutState.overlayMenuActive || layoutState.staticMenuMobileActive
  )

  const isDarkTheme = computed(() => layoutConfig.darkTheme)

  watch(isDarkTheme, (newVal) => {
    if (newVal) {
      layoutConfig.theme = 'aura-dark-purple'
    } else {
      layoutConfig.theme = 'aura-light-purple'
    }
  })

  onMounted(() => {
    document.documentElement.classList.add('aura-dark-purple')

    !localStorage.getItem('theme') && localStorage.setItem('theme', 'light')
    layoutConfig.darkTheme = localStorage.getItem('theme') === 'dark'
  })

  return {
    layoutConfig: toRefs(layoutConfig),
    layoutState: toRefs(layoutState),
    changeThemeSettings,
    setScale,
    onMenuToggle,
    isSidebarActive,
    isDarkTheme,
    setActiveMenuItem
  }
}
