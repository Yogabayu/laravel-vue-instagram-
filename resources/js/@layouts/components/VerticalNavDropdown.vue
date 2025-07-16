<template id="VerticalNavDropdown">
    <div class="dropdown nav-link" @mouseover="hover = true" @mouseleave="hover = false">
        <div class="dropdown-title">
            <!-- Icon for the dropdown -->
            <VIcon :icon="icon" class="nav-item-icon" />
            <!-- Title for the dropdown -->
            <span>{{ title }}</span>
            <!-- Spacer to push the chevron icon to the right -->
            <span class="spacer"></span>
            <!-- Chevron icon indicating dropdown, dynamically change on hover -->
            <VIcon :icon="chevronIcon" class="nav-item-icon chevron" />
        </div>
        <div class="dropdown-content">
            <slot></slot> <!-- Slot to inject dropdown items -->
        </div>
    </div>
</template>

<script>
export default {
    name: 'VerticalNavDropdown',
    props: {
        title: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            hover: false // Reactive property to track hover state
        };
    },
    computed: {
        chevronIcon() {
            return this.hover ? 'bx-chevron-up' : 'bx-chevron-down';
        }
    }
}
</script>

<style scoped>
.dropdown {
    padding: 0.2rem 0;
}

.dropdown-title {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.dropdown-title .nav-item-icon {
    margin-right: 0.5rem;
}

.dropdown-title .spacer {
    flex: 1;
    /* Takes up the remaining space, pushing chevron to the right */
}

.dropdown-title .chevron {
    margin-left: auto;
    /* Ensures the chevron is at the rightmost position */
}

.dropdown-content {
    margin-left: 1rem;
    display: none;
    opacity: 0;
    /* Mulai dengan opacity 0 */
    max-height: 0;
    /* Mulai dengan height 0 */
    overflow: hidden;
    /* Sembunyikan konten yang overflow */
    transition: max-height 0.5s ease-out, opacity 0.5s ease-out;
    /* Transisi smooth */
}

.dropdown:hover .dropdown-content {
    display: block;
    opacity: 1;
    /* Tampilkan dengan opacity 1 */
    max-height: 500px;
    /* Atur max-height yang sesuai dengan konten dropdown */
}
</style>
