import { ref, onMounted, onBeforeUnmount } from 'vue'

export function useStationTimer() {
    const tick = ref(0)
    let timerInterval = null

    onMounted(() => {
        timerInterval = window.setInterval(() => {
            tick.value++
        }, 1000)
    })

    onBeforeUnmount(() => {
        if (timerInterval) {
            clearInterval(timerInterval)
        }
    })

    const formatSeconds = (totalSeconds) => {
        if (totalSeconds < 0) totalSeconds = 0
        
        const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0')
        const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0')
        const s = String(totalSeconds % 60).padStart(2, '0')

        return `${h}:${m}:${s}`
    }

    const getElapsedSeconds = (session, isPlaying) => {
        if (!session) return 0
        
        if (isPlaying && session.start_time) {
            const startTime = new Date(session.start_time).getTime()
            const now = Date.now()
            const runningSeconds = Math.floor((now - startTime) / 1000)
            const pausedSeconds = Math.floor(Number(session.duration_minutes || 0) * 60)
            return pausedSeconds + runningSeconds
        }
        
        return Math.floor(Number(session.elapsed_minutes || 0) * 60)
    }

    const getRemainingSeconds = (session, isPlaying) => {
        if (!session || session.billing_type !== 'package' || !session.package_minutes) {
            return null
        }
        
        const totalElapsed = getElapsedSeconds(session, isPlaying)
        const packageSeconds = session.package_minutes * 60
        
        return Math.max(0, packageSeconds - totalElapsed)
    }

    const isPackageMode = (session) => {
        return session && session.billing_type === 'package' && session.package_minutes > 0
    }

    const isExpired = (session, isPlaying) => {
        if (!session) return false
        
        if (session.is_expired) return true
        
        if (isPackageMode(session) && isPlaying) {
            const remaining = getRemainingSeconds(session, isPlaying)
            return remaining !== null && remaining <= 0
        }
        
        return false
    }

    const getDisplayTimer = (session, isPlaying) => {
        if (!session) return '00:00:00'

        if (isPackageMode(session)) {
            const remaining = getRemainingSeconds(session, isPlaying)
            
            if (remaining !== null && remaining <= 0) {
                return '00:00:00'
            }
            
            return formatSeconds(remaining)
        }

        const elapsed = getElapsedSeconds(session, isPlaying)
        return formatSeconds(elapsed)
    }

    const getRealtimeSubtotal = (session, isPlaying, pricePerHour) => {
        if (!session || !pricePerHour) return 0

        const elapsedSeconds = getElapsedSeconds(session, isPlaying)
        
        return Math.floor((pricePerHour / 3600) * elapsedSeconds)
    }

    return {
        tick,
        formatSeconds,
        getElapsedSeconds,
        getRemainingSeconds,
        isPackageMode,
        isExpired,
        getDisplayTimer,
        getRealtimeSubtotal,
    }
}