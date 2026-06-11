<template>
  <div ref="containerRef" class="notification-bell" style="position: relative; display: inline-block;">
    <button
      @click="toggleDropdown"
      style="background: none; border: none; cursor: pointer; position: relative; padding: 4px;"
      aria-label="Notifications"
    >
      <span style="font-size: 1.4rem;">&#128276;</span>
      <span
        v-if="unreadCount > 0"
        style="
          position: absolute;
          top: 0;
          right: 0;
          background: #e53e3e;
          color: #fff;
          border-radius: 9999px;
          min-width: 18px;
          height: 18px;
          font-size: 0.7rem;
          font-weight: 700;
          display: flex;
          align-items: center;
          justify-content: center;
          padding: 0 4px;
          line-height: 1;
        "
      >{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
    </button>

    <div
      v-if="open"
      style="
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        width: 320px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
        z-index: 9999;
        overflow: hidden;
      "
    >
      <div style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 600; font-size: 0.95rem;">
        Notifications
      </div>

      <div v-if="recentNotifications.length === 0" style="padding: 24px 16px; text-align: center; color: #718096; font-size: 0.9rem;">
        No notifications
      </div>

      <ul v-else style="list-style: none; margin: 0; padding: 0;">
        <li
          v-for="notification in recentNotifications"
          :key="notification.id"
          @click="navigateTo(notification.data && notification.data.link)"
          style="
            padding: 12px 16px;
            border-bottom: 1px solid #f0f4f8;
            cursor: pointer;
            transition: background 0.15s;
          "
          @mouseenter="e => e.currentTarget.style.background = '#f7fafc'"
          @mouseleave="e => e.currentTarget.style.background = ''"
        >
          <div style="font-weight: 600; font-size: 0.9rem; color: #2d3748; margin-bottom: 2px;">
            {{ notification.data && notification.data.title }}
          </div>
          <div style="font-size: 0.85rem; color: #4a5568; margin-bottom: 4px; white-space: pre-wrap; word-break: break-word;">
            {{ notification.data && notification.data.body }}
          </div>
          <div style="font-size: 0.75rem; color: #a0aec0;">
            {{ relativeTime(notification.created_at) }}
          </div>
        </li>
      </ul>

      <div style="padding: 10px 16px; text-align: right; border-top: 1px solid #e2e8f0;">
        <button
          @click.stop="markAllRead"
          style="
            background: none;
            border: none;
            color: #3182ce;
            cursor: pointer;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0;
          "
        >
          Mark all read
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const page = usePage()

const unreadCount = computed(() => page.props.unreadCount ?? 0)
const notifications = computed(() => page.props.notifications ?? [])

const recentNotifications = computed(() => notifications.value.slice(0, 5))

const open = ref(false)
const containerRef = ref(null)

function toggleDropdown() {
  open.value = !open.value
}

function navigateTo(link) {
  if (link) {
    open.value = false
    router.visit(link)
  }
}

function markAllRead() {
  router.post('/notifications/read-all', {}, {
    preserveScroll: true,
    onSuccess: () => {
      open.value = false
    },
  })
}

function relativeTime(dateString) {
  if (!dateString) return ''
  const now = new Date()
  const date = new Date(dateString)
  const diffMs = now - date
  const diffSec = Math.floor(diffMs / 1000)
  if (diffSec < 60) return diffSec <= 1 ? 'just now' : `${diffSec}s ago`
  const diffMin = Math.floor(diffSec / 60)
  if (diffMin < 60) return `${diffMin}m ago`
  const diffHr = Math.floor(diffMin / 60)
  if (diffHr < 24) return `${diffHr}h ago`
  const diffDay = Math.floor(diffHr / 24)
  if (diffDay < 30) return `${diffDay}d ago`
  const diffMon = Math.floor(diffDay / 30)
  if (diffMon < 12) return `${diffMon}mo ago`
  const diffYr = Math.floor(diffMon / 12)
  return `${diffYr}y ago`
}

function handleOutsideClick(event) {
  if (containerRef.value && !containerRef.value.contains(event.target)) {
    open.value = false
  }
}

onMounted(() => {
  document.addEventListener('mousedown', handleOutsideClick)
})

onBeforeUnmount(() => {
  document.removeEventListener('mousedown', handleOutsideClick)
})
</script>
