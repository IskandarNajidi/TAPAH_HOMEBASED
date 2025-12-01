<template>
  <div class="tenant-browse-container">
    
    <div class="header-nav-wrapper">
      <HeaderNav :user="user" />
    </div>

    <!-- Hero Header Section -->
    <header class="hero-header-tenant">
      <div class="hero-background">
        <div class="hero-image" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&q=80')"></div>
        <div class="hero-gradient-overlay"></div>
      </div>
      
      <div class="hero-content">
        <div class="hero-badge">
          <svg class="badge-icon" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
          </svg>
          <span>Property Discovery</span>
        </div>
        <h1 class="hero-title">Find Your Perfect Home</h1>
        <p class="hero-subtitle">Browse available rental properties in Tapah</p>
        
        <!-- Search Bar -->
        <div class="search-bar-tenant">
          <svg class="search-icon" viewBox="0 0 24 24">
            <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
          </svg>
          <input 
            type="text" 
            placeholder="Search by property name or location..." 
            class="search-input" 
            v-model="searchQuery"
          >
        </div>

        <!-- Quick Filters -->
        <div class="quick-filters">
          <button 
            v-for="filter in quickFilters" 
            :key="filter.value"
            :class="['filter-btn', { active: activeFilter === filter.value }]"
            @click="activeFilter = filter.value"
          >
            {{ filter.label }}
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="main-content-tenant">
      <div class="content-container">
        
        <!-- Loading State -->
        <div v-if="loading" class="state-container">
          <div class="loading-spinner-wrapper">
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
            <div class="loading-spinner-ring"></div>
          </div>
          <p class="loading-text">Finding perfect properties for you...</p>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredProperties.length === 0" class="state-container">
          <div class="empty-illustration">
            <svg viewBox="0 0 200 200" class="illustration-svg">
              <circle cx="100" cy="100" r="80" fill="#E8F8F5"/>
              <path fill="#1ABC9C" d="M100,40 L140,70 L140,130 L100,160 L60,130 L60,70 Z"/>
              <path fill="#16A085" d="M100,50 L130,70 L130,120 L100,140 L70,120 L70,70 Z"/>
              <circle cx="100" cy="95" r="15" fill="#fff"/>
            </svg>
          </div>
          <h3 class="empty-title">No Properties Found</h3>
          <p class="empty-description">Try adjusting your search or filters to find more properties</p>
        </div>

        <!-- Properties Grid -->
        <div v-else class="properties-grid-tenant">
          <div
            v-for="property in filteredProperties"
            :key="property.id"
            class="property-card-instagram"
            @click="openPropertyDetails(property)"
          >
            <!-- Main Image -->
            <div class="card-image-instagram">
              <img :src="property.mainImage" :alt="property.title" />
              
              <!-- Image Count Badge -->
              <div class="image-count-badge">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <span>{{ property.totalImages }}</span>
              </div>

              <!-- Favorite Heart Button -->
              <button 
                class="favorite-heart-btn-grid" 
                :class="{ active: isPropertySaved(property.id) }" 
                @click="togglePropertySave(property, $event)"
                title="Add to favorites"
              >
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                </svg>
              </button>

              <!-- Hover Overlay -->
              <div class="card-overlay-instagram">
                <div class="overlay-content">
                  <h3>{{ property.title }}</h3>
                  <p class="price-large">RM {{ formatNumber(property.payment?.amount || 0) }}<span>/month</span></p>
                </div>
              </div>
            </div>

            <!-- Card Info -->
            <div class="card-info-instagram">
              <h4 class="property-title-instagram">{{ property.title }}</h4>
              <p class="property-location-instagram">
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
                {{ property.address || 'Location not specified' }}
              </p>
              
              <!-- Quick Stats -->
              <div class="card-stats-instagram">
                <span class="stat">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V7H1v13h2v-2h18v2h2V10c0-2.21-1.79-4-4-4z"/>
                  </svg>
                  {{ property.available_rooms || 0 }} Beds
                </span>
                <span class="stat">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                  </svg>
                  {{ property.total_bathrooms || property.bathrooms || 0 }} Baths
                </span>
                <span class="stat">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                  {{ property.current_tenants || 0 }}/{{ property.tenants_needed || 0 }}
                </span>
              </div>

              <!-- Price -->
              <div class="card-price-instagram">
                <span class="amount">RM {{ formatNumber(property.payment?.amount || 0) }}</span>
                <span class="period">/month</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- REDESIGNED Property Details Modal -->
    <div v-if="viewingProperty" class="property-modal-overlay" @click="closeDetails">
      <div class="property-modal-modern" @click.stop>
        
        <!-- Close Button -->
        <button class="modal-close-modern" @click="closeDetails">
          <svg class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>

        <!-- Quick Actions (Top Right of Hero) -->
        <div class="quick-actions-hero">
          <button class="action-icon-btn" @click="toggleSave" :class="{ saved: isCurrentPropertySaved }">
            <svg v-if="isCurrentPropertySaved" class="icon" viewBox="0 0 24 24">
              <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
            <svg v-else class="icon" viewBox="0 0 24 24">
              <path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/>
            </svg>
          </button>
          <button class="action-icon-btn" @click="shareProperty">
            <svg class="icon" viewBox="0 0 24 24">
              <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
            </svg>
          </button>
        </div>

        <div class="modal-content-wrapper">
          
          <!-- HERO SECTION: Full-Width Image Gallery -->
          <section class="hero-gallery-section">
            
            <!-- Image Category Pills -->
            <div class="image-category-pills">
              <button 
                v-for="category in imageCategories" 
                :key="category"
                :class="['category-pill', { active: currentCategory === category }]"
                @click="filterImagesByCategory(category)"
              >
                {{ category }} <span class="pill-count">({{ getImageCountByCategory(category) }})</span>
              </button>
            </div>

            <!-- Main Hero Image -->
            <div class="hero-image-container" @touchstart="handleTouchStart" @touchend="handleTouchEnd">
              <img 
                :src="currentDisplayImage.url" 
                :alt="currentDisplayImage.caption"
                class="hero-image-main"
              />

              <!-- Navigation Arrows on Image -->
              <button 
                v-if="filteredImages.length > 1"
                class="hero-nav-arrow left" 
                @click="prevImage"
              >
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
              </button>
              <button 
                v-if="filteredImages.length > 1"
                class="hero-nav-arrow right" 
                @click="nextImage"
              >
                <svg class="icon" viewBox="0 0 24 24">
                  <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                </svg>
              </button>

              <!-- Image Counter Overlay -->
              <div class="hero-image-counter">
                {{ currentImageIndex + 1 }} / {{ filteredImages.length }}
              </div>

              <!-- Auto-play Toggle -->
              <button class="hero-autoplay-toggle" @click="toggleAutoplay">
                <svg v-if="isAutoPlaying" class="icon" viewBox="0 0 24 24">
                  <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                </svg>
                <svg v-else class="icon" viewBox="0 0 24 24">
                  <path d="M8 5v14l11-7z"/>
                </svg>
              </button>
            </div>

            <!-- Thumbnail Strip -->
            <div class="thumbnail-strip" v-if="filteredImages.length > 1">
              <button
                v-for="(img, idx) in filteredImages.slice(0, 6)"
                :key="idx"
                :class="['thumbnail-item', { active: idx === currentImageIndex }]"
                @click="jumpToImage(idx)"
              >
                <img :src="img.url" :alt="img.caption" />
              </button>
              <div v-if="filteredImages.length > 6" class="thumbnail-more">
                +{{ filteredImages.length - 6 }}
              </div>
            </div>
          </section>

          <!-- TWO-COLUMN CONTENT SECTION -->
          <section class="content-two-column">
            
            <!-- LEFT COLUMN: Description & Details -->
            <section class="content-left-column">
              
              <!-- Property Header -->
              <header class="property-header-redesigned">
                <h1 class="property-title-redesigned">{{ viewingProperty.title }}</h1>
                <div class="property-location-redesigned">
                  <svg class="location-pin-icon-redesigned" viewBox="0 0 24 24">
                    <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                  </svg>
                  <span class="location-text-redesigned">{{ viewingProperty.address }}</span>
                </div>
              </header>

              <!-- Property Description -->
              <div class="description-section" v-if="viewingProperty.description">
                <h3 class="section-title-modern">About This Property</h3>
                <p class="description-text">{{ viewingProperty.description }}</p>
              </div>

              <!-- Amenities -->
              <div class="amenities-section" v-if="viewingProperty.amenities && viewingProperty.amenities.length > 0">
                <h3 class="section-title-modern">✨ Amenities</h3>
                <div class="amenities-grid-redesigned">
                  <div 
                    v-for="(amenity, index) in viewingProperty.amenities" 
                    :key="amenity"
                    class="amenity-card-redesigned"
                    :style="{ animationDelay: `${index * 0.1}s` }"
                  >
                    <div class="amenity-icon-wrapper-redesigned">
                      <svg class="amenity-check-icon-redesigned" viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                      </svg>
                    </div>
                    <span class="amenity-text-redesigned">{{ amenity }}</span>
                  </div>
                </div>
              </div>

              <!-- Location Details -->
              <div class="location-section">
                <h3 class="section-title-modern">📍 Location & Neighborhood</h3>
                
                <div class="location-info-grid-redesigned">
                  
                  <div class="location-card-redesigned" v-if="viewingProperty.distance_ui_tm">
                    <div class="location-icon-circle-redesigned">
                      <svg class="location-icon-redesigned" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                    </div>
                    <div class="location-content-redesigned">
                      <strong class="location-label-redesigned">Distance from UiTM</strong>
                      <span class="location-value-redesigned">{{ viewingProperty.distance_ui_tm }}</span>
                    </div>
                  </div>

                  <div class="location-card-redesigned" v-if="viewingProperty.nearby_shop">
                    <div class="location-icon-circle-redesigned">
                      <svg class="location-icon-redesigned" viewBox="0 0 24 24">
                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                      </svg>
                    </div>
                    <div class="location-content-redesigned">
                      <strong class="location-label-redesigned">Nearby Shops</strong>
                      <span class="location-value-redesigned">{{ viewingProperty.nearby_shop }}</span>
                    </div>
                  </div>

                  <div class="location-card-redesigned" v-if="viewingProperty.nearby_facility">
                    <div class="location-icon-circle-redesigned">
                      <svg class="location-icon-redesigned" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 4.17 4.42 9.92 6.24 12.11.4.48 1.13.48 1.53 0C14.58 18.92 19 13.17 19 9c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                    </div>
                    <div class="location-content-redesigned">
                      <strong class="location-label-redesigned">Nearby Facilities</strong>
                      <span class="location-value-redesigned">{{ viewingProperty.nearby_facility }}</span>
                    </div>
                  </div>
                  
                </div>

                <div v-if="viewingProperty.location_advantages" class="location-advantages-box-redesigned">
                  <svg class="advantage-icon-redesigned" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                  <p class="location-advantages-text-redesigned">{{ viewingProperty.location_advantages }}</p>
                </div>
                
              </div>

              <!-- Rooms Details -->
              <div class="rooms-section" v-if="viewingProperty.rooms && viewingProperty.rooms.length > 0">
                <h3 class="section-title-modern">🚪 Available Rooms</h3>
                <div class="rooms-grid-redesigned">
                  <div 
                    v-for="room in viewingProperty.rooms" 
                    :key="room.id"
                    class="room-card-redesigned"
                    @click="showRoomImages(room)"
                  >
                    <div class="room-card-header-redesigned">
                      <h4 class="room-title-redesigned">{{ room.room_type || 'Room' }}</h4>
                      <span class="room-price-redesigned" v-if="room.price">RM {{ formatNumber(room.price) }}</span>
                    </div>

                    <div class="room-details-redesigned">
                      <div class="room-detail-row-redesigned">
                        <svg class="room-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="room-detail-text-redesigned">Capacity: <strong>{{ room.capacity || 1 }}</strong></span>
                      </div>
                      <div class="room-detail-row-redesigned">
                        <svg class="room-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V7H1v13h2v-2h18v2h2V10c0-2.21-1.79-4-4-4z"/>
                        </svg>
                        <span class="room-detail-text-redesigned"><strong>{{ room.bed_type || 'Standard Bed' }}</strong></span>
                      </div>
                      <div class="room-detail-row-redesigned">
                        <svg class="room-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span class="room-detail-text-redesigned"><strong>{{ room.bathroom_type || 'Shared' }}</strong></span>
                      </div>
                    </div>

                    <button class="room-cta-button-redesigned">
                      <svg class="cta-icon-redesigned" viewBox="0 0 24 24">
                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                      </svg>
                      View Room Photos
                    </button>
                  </div>
                </div>
              </div>

              <!-- Common Areas -->
              <div class="common-areas-section" v-if="viewingProperty.additionalRooms && viewingProperty.additionalRooms.length > 0">
                <h3 class="section-title-modern">🏠 Common Areas</h3>
                <div class="common-areas-grid-redesigned">
                  <div 
                    v-for="space in viewingProperty.additionalRooms" 
                    :key="space.id"
                    class="common-area-card-redesigned"
                    @click="showSpaceImages(space)"
                  >
                    <div class="common-area-image-wrapper-redesigned">
                      <img :src="formatImageUrl(space.image_path)" :alt="space.name" class="common-area-image-redesigned" />
                      <div class="common-area-overlay-redesigned">
                        <svg class="overlay-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                        <span class="overlay-text-redesigned">View Photos</span>
                      </div>
                    </div>
                    <div class="common-area-label-redesigned">
                      <span class="common-area-name-redesigned">{{ space.name }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Contract Info -->
              <div class="contract-section">
                <h3 class="section-title-modern">📄 Contract Information</h3>
                
                <div class="contract-card-redesigned">
                  <div class="contract-icon-circle-redesigned">
                    <svg class="contract-icon-redesigned" viewBox="0 0 24 24">
                      <path d="M9 11H7v2h2v-2zm4 0h-2v2h2v-2zm4 0h-2v2h2v-2zm2-7h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V9h14v11z"/>
                    </svg>
                  </div>
                  <div class="contract-content-redesigned">
                    <span class="contract-label-redesigned">Contract Duration</span>
                    <strong class="contract-value-redesigned">{{ viewingProperty.contract_duration || 'Flexible / Negotiable' }}</strong>
                  </div>
                </div>
                
              </div>
            </section>

            <!-- RIGHT COLUMN: Pricing & Sticky CTA -->
            <aside class="content-right-sidebar">
              
              <div class="sidebar-sticky-container">
                
                <div class="booking-summary-box-redesigned">
                  
                  <div class="price-section-redesigned">
                    <div class="price-label-redesigned">Monthly Rent</div>
                    <div class="price-main-redesigned">
                      <span class="price-amount-redesigned">RM {{ formatNumber(viewingProperty.payment?.amount || 0) }}</span>
                      <span class="price-period-redesigned">/month</span>
                    </div>
                  </div>

                  <div class="summary-divider-redesigned"></div>

                  <div class="payment-details-redesigned">
                    <div class="payment-row-redesigned">
                      <span class="payment-label-redesigned">Deposit:</span>
                      <strong class="payment-value-redesigned">RM {{ formatNumber(viewingProperty.payment?.deposit || 0) }}</strong>
                    </div>
                    <div class="payment-row-redesigned">
                      <span class="payment-label-redesigned">Utilities:</span>
                      <strong class="payment-value-redesigned">{{ viewingProperty.payment?.utility || 'Included' }}</strong>
                    </div>
                  </div>

                  <div class="summary-divider-redesigned"></div>

                  <div class="key-stats-box-redesigned">
                    <div class="stat-item-redesigned">
                      <div class="stat-icon-circle-redesigned">
                        <svg class="stat-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V7H1v13h2v-2h18v2h2V10c0-2.21-1.79-4-4-4z"/>
                        </svg>
                      </div>
                      <div class="stat-content-redesigned">
                        <strong class="stat-number-redesigned">{{ animatedStats.beds }}</strong>
                        <span class="stat-label-redesigned">Beds Available</span>
                      </div>
                    </div>

                    <div class="stat-item-redesigned">
                      <div class="stat-icon-circle-redesigned">
                        <svg class="stat-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                        </svg>
                      </div>
                      <div class="stat-content-redesigned">
                        <strong class="stat-number-redesigned">{{ animatedStats.bathrooms }}</strong>
                        <span class="stat-label-redesigned">Bathrooms</span>
                      </div>
                    </div>

                    <div class="stat-item-redesigned">
                      <div class="stat-icon-circle-redesigned">
                        <svg class="stat-icon-redesigned" viewBox="0 0 24 24">
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                      </div>
                      <div class="stat-content-redesigned">
                        <strong class="stat-number-redesigned">{{ animatedStats.tenants }}/{{ viewingProperty.tenants_needed || 0 }}</strong>
                        <span class="stat-label-redesigned">Tenants</span>
                      </div>
                    </div>
                  </div>

                </div>

                <button class="btn-whatsapp-redesigned" @click="contactLandlord">
                  <svg class="whatsapp-icon-redesigned" viewBox="0 0 24 24">
                    <path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 11H7V9h2v2zm4 0h-2V9h2v2zm4 0h-2V9h2v2z"/>
                  </svg>
                  WhatsApp Landlord
                </button>

                <p class="response-time-redesigned">
                  <svg class="clock-icon-redesigned" viewBox="0 0 24 24">
                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                  </svg>
                  Typical response within 1 hour
                </p>

              </div>

            </aside>

          </section>

        </div>

      </div>
    </div>

    <!-- Toast Notification -->
    <transition name="toast-fade">
      <div v-if="toast.visible" class="toast-notification" :class="toast.type">
        <div class="toast-icon">
          <svg v-if="toast.type === 'success'" class="icon" viewBox="0 0 24 24">
            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
          </svg>
          <svg v-else class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </div>
        <div class="toast-content">
          <p class="toast-message">{{ toast.message }}</p>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch, nextTick } from 'vue'
import axios from 'axios'
import HeaderNav from '../components/HeaderNav.vue'

// Core state
const user = ref(JSON.parse(localStorage.getItem('user')) || { name: 'Guest' })
const properties = ref([])
const loading = ref(false)
const viewingProperty = ref(null)
const searchQuery = ref('')
const activeFilter = ref('all')

// Image carousel state
const currentImageIndex = ref(0)
const currentCategory = ref('All')
const isAutoPlaying = ref(true)
let autoPlayInterval = null

// Touch events for mobile
let touchStartX = 0
let touchEndX = 0

// Saved properties
const savedProperties = ref(JSON.parse(localStorage.getItem('savedProperties') || '[]'))

// Toast notification
const toast = ref({
  visible: false,
  message: '',
  type: 'success'
})

// Quick filters
const quickFilters = ref([
  { label: 'All Properties', value: 'all' },
  { label: 'Low Price', value: 'low-price' },
  { label: 'Near UiTM', value: 'near-uitm' },
  { label: 'Fully Furnished', value: 'furnished' }
])

// Image categories
const imageCategories = ref(['All', 'Property', 'Rooms', 'Bathrooms', 'Common Areas'])

// Animated number counter
const animatedStats = ref({
  beds: 0,
  bathrooms: 0,
  tenants: 0
})

// Computed: Filtered properties
const filteredProperties = computed(() => {
  let filtered = properties.value

  // Search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(p => 
      p.title?.toLowerCase().includes(query) ||
      p.address?.toLowerCase().includes(query)
    )
  }

  // Quick filter
  if (activeFilter.value === 'low-price') {
    filtered = filtered.sort((a, b) => (a.payment?.amount || 0) - (b.payment?.amount || 0))
  } else if (activeFilter.value === 'near-uitm') {
    filtered = filtered.filter(p => p.distance_ui_tm?.includes('minute'))
  } else if (activeFilter.value === 'furnished') {
    filtered = filtered.filter(p => 
      p.amenities?.some(a => a.toLowerCase().includes('furnish'))
    )
  }

  return filtered
})

// Computed: Filtered images by category
const filteredImages = computed(() => {
  if (!viewingProperty.value) return []
  
  if (currentCategory.value === 'All') {
    return viewingProperty.value.allImages || []
  }
  
  return (viewingProperty.value.allImages || []).filter(img => 
    img.category === currentCategory.value
  )
})

// Computed: Current display image
const currentDisplayImage = computed(() => {
  return filteredImages.value[currentImageIndex.value] || { url: '/default-house.jpg', caption: '' }
})

// Function to check if ANY property is saved (for grid cards)
function isPropertySaved(propertyId) {
  return savedProperties.value.includes(propertyId)
}

// Computed: Is CURRENT property saved (for modal only)
const isCurrentPropertySaved = computed(() => {
  return savedProperties.value.includes(viewingProperty.value?.id)
})

// Toggle property save (for grid cards)
function togglePropertySave(property, event) {
  if (event) event.stopPropagation() // Prevent card click
  
  const propertyId = property.id
  
  if (savedProperties.value.includes(propertyId)) {
    // Remove from favorites
    savedProperties.value = savedProperties.value.filter(id => id !== propertyId)
    showToast('Removed from favorites', 'info')
    
    // Remove timestamp
    const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
    delete timestamps[propertyId]
    localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))
  } else {
    // Add to favorites
    savedProperties.value.push(propertyId)
    showToast('❤️ Added to favorites!', 'success')
    
    // Add timestamp
    const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
    timestamps[propertyId] = new Date().toISOString()
    localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))
  }
  
  // Save to localStorage
  localStorage.setItem('savedProperties', JSON.stringify(savedProperties.value))
}

// Toggle save (for modal)
function toggleSave() {
  const propertyId = viewingProperty.value.id
  
  if (isCurrentPropertySaved.value) {
    savedProperties.value = savedProperties.value.filter(id => id !== propertyId)
    showToast('Removed from favorites', 'info')
    
    // Remove timestamp
    const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
    delete timestamps[propertyId]
    localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))
  } else {
    savedProperties.value.push(propertyId)
    showToast('❤️ Saved to favorites!', 'success')
    
    // Add timestamp
    const timestamps = JSON.parse(localStorage.getItem('favoritedTimestamps') || '{}')
    timestamps[propertyId] = new Date().toISOString()
    localStorage.setItem('favoritedTimestamps', JSON.stringify(timestamps))
  }
  
  localStorage.setItem('savedProperties', JSON.stringify(savedProperties.value))
}

// Counter animation function
function animateValue(obj, key, start, end, duration) {
  let startTimestamp = null
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp
    const progress = Math.min((timestamp - startTimestamp) / duration, 1)
    obj[key] = Math.floor(progress * (end - start) + start)
    if (progress < 1) {
      window.requestAnimationFrame(step)
    }
  }
  window.requestAnimationFrame(step)
}

// Watch for modal opening and animate numbers
watch(viewingProperty, (newVal) => {
  if (newVal && isAutoPlaying.value) {
    startAutoPlay()
    
    // Animate stats numbers
    animateValue(animatedStats.value, 'beds', 0, newVal.available_rooms || 0, 1000)
    animateValue(animatedStats.value, 'bathrooms', 0, newVal.total_bathrooms || newVal.bathrooms || 0, 1200)
    animateValue(animatedStats.value, 'tenants', 0, newVal.current_tenants || 0, 1400)
  } else {
    stopAutoPlay()
  }
})

// Format number with commas
function formatNumber(num) {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") || '0'
}

// Format image URL
function formatImageUrl(path) {
  if (!path) return '/default-house.jpg'
  return path.startsWith('http')
    ? path
    : `http://127.0.0.1:8000${String(path).replace(/\\/g, '')}`
}

// Toast notification
function showToast(message, type = 'success') {
  toast.value.message = message
  toast.value.type = type
  toast.value.visible = true
  
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}

// Fetch properties
async function fetchProperties() {
  loading.value = true
  
  try {
    const propRes = await axios.get('/api/properties')
    let allProperties = Array.isArray(propRes.data) 
      ? propRes.data 
      : (propRes.data?.data || [])
    
    // Filter to only available properties
    allProperties = allProperties.filter(p => p.available_rooms > 0)
    
    // Fetch details for each property
    const propertiesWithDetails = await Promise.all(
      allProperties.map(async (property) => {
        // Fetch payment
        const payRes = await axios.get(`/api/payments?property_id=${property.id}`)
        const payment = Array.isArray(payRes.data)
          ? payRes.data.find(x => x.property_id === property.id) || payRes.data[0]
          : payRes.data || { amount: 0, deposit: 0, utility: 'Included' }
        
        // Fetch rooms
        const roomsRes = await axios.get(`/api/properties/${property.id}/rooms`)
        const rooms = Array.isArray(roomsRes.data) ? roomsRes.data : []
        
        // Fetch additional rooms
        const additionalRes = await axios.get(`/api/properties/${property.id}/additional-rooms`)
        let additionalRooms = []
        const rawData = additionalRes.data
        if (Array.isArray(rawData)) {
          additionalRooms = rawData
        } else if (rawData && Array.isArray(rawData.data)) {
          additionalRooms = rawData.data
        }
        
        // Collect all images
        let allImages = []
        
        // Property images
        let propImages = property.images
        if (typeof propImages === 'string') {
          try { propImages = JSON.parse(propImages) } catch {}
        }
        if (Array.isArray(propImages)) {
          propImages.forEach(img => {
            allImages.push({
              url: formatImageUrl(img),
              category: 'Property',
              caption: property.title
            })
          })
        }
        
        // Room images
        rooms.forEach(room => {
          if (room.images && Array.isArray(room.images)) {
            room.images.forEach(img => {
              allImages.push({
                url: formatImageUrl(img.image_path),
                category: img.type === 'bathroom' ? 'Bathrooms' : 'Rooms',
                caption: `${room.room_type} - ${img.type === 'bathroom' ? 'Bathroom' : 'Room'}`,
                roomId: room.id
              })
            })
          }
        })
        
        // Additional room images
        additionalRooms.forEach(space => {
          if (space.image_path) {
            allImages.push({
              url: formatImageUrl(space.image_path),
              category: 'Common Areas',
              caption: space.name
            })
          }
        })
        
        // Default image if none
        if (allImages.length === 0) {
          allImages.push({
            url: '/default-house.jpg',
            category: 'Property',
            caption: property.title
          })
        }
        
        return {
          ...property,
          payment,
          rooms,
          additionalRooms,
          allImages,
          totalImages: allImages.length,
          mainImage: allImages[0]?.url || '/default-house.jpg'
        }
      })
    )
    
    properties.value = propertiesWithDetails
    
  } catch (err) {
    console.error('Error fetching properties:', err)
    showToast('Failed to load properties', 'error')
  } finally {
    loading.value = false
  }
}

// Open property details
function openPropertyDetails(property) {
  viewingProperty.value = property
  currentImageIndex.value = 0
  currentCategory.value = 'All'
  document.body.style.overflow = 'hidden'
  
  if (isAutoPlaying.value) {
    startAutoPlay()
  }
}

// Close details
function closeDetails() {
  viewingProperty.value = null
  stopAutoPlay()
  document.body.style.overflow = 'auto'
}

// Image navigation
function nextImage() {
  if (filteredImages.value.length > 1) {
    currentImageIndex.value = (currentImageIndex.value + 1) % filteredImages.value.length
  }
}

function prevImage() {
  if (filteredImages.value.length > 1) {
    currentImageIndex.value = (currentImageIndex.value - 1 + filteredImages.value.length) % filteredImages.value.length
  }
}

function jumpToImage(index) {
  currentImageIndex.value = index
}

// Filter images by category
function filterImagesByCategory(category) {
  currentCategory.value = category
  currentImageIndex.value = 0
}

// Get image count by category
function getImageCountByCategory(category) {
  if (!viewingProperty.value) return 0
  if (category === 'All') return viewingProperty.value.allImages?.length || 0
  return (viewingProperty.value.allImages || []).filter(img => img.category === category).length
}

// Show room images - WITH AUTO SCROLL
function showRoomImages(room) {
  currentCategory.value = 'Rooms'
  const roomImageIndex = filteredImages.value.findIndex(img => img.roomId === room.id)
  if (roomImageIndex !== -1) {
    currentImageIndex.value = roomImageIndex
  }
  
  // Scroll modal to top to show images
  nextTick(() => {
    const modalContent = document.querySelector('.modal-content-wrapper')
    if (modalContent) {
      modalContent.scrollTo({ 
        top: 0, 
        behavior: 'smooth' 
      })
    }
  })
}

// Show space images - WITH AUTO SCROLL
function showSpaceImages(space) {
  currentCategory.value = 'Common Areas'
  const spaceImageIndex = filteredImages.value.findIndex(img => img.caption === space.name)
  if (spaceImageIndex !== -1) {
    currentImageIndex.value = spaceImageIndex
  }
  
  // Scroll modal to top to show images
  nextTick(() => {
    const modalContent = document.querySelector('.modal-content-wrapper')
    if (modalContent) {
      modalContent.scrollTo({ 
        top: 0, 
        behavior: 'smooth' 
      })
    }
  })
}

// Auto-play
function startAutoPlay() {
  stopAutoPlay()
  autoPlayInterval = setInterval(() => {
    nextImage()
  }, 3000)
}

function stopAutoPlay() {
  if (autoPlayInterval) {
    clearInterval(autoPlayInterval)
    autoPlayInterval = null
  }
}

function toggleAutoplay() {
  isAutoPlaying.value = !isAutoPlaying.value
  if (isAutoPlaying.value) {
    startAutoPlay()
  } else {
    stopAutoPlay()
  }
}

// Touch handlers for mobile swipe
function handleTouchStart(e) {
  touchStartX = e.changedTouches[0].screenX
}

function handleTouchEnd(e) {
  touchEndX = e.changedTouches[0].screenX
  handleSwipe()
}

function handleSwipe() {
  const swipeThreshold = 50
  
  if (touchEndX < touchStartX - swipeThreshold) {
    nextImage()
  }
  
  if (touchEndX > touchStartX + swipeThreshold) {
    prevImage()
  }
}

// Keyboard navigation
function handleKeyPress(e) {
  if (!viewingProperty.value) return
  
  switch(e.key) {
    case 'ArrowLeft':
      prevImage()
      break
    case 'ArrowRight':
      nextImage()
      break
    case 'Escape':
      closeDetails()
      break
    case ' ':
      e.preventDefault()
      toggleAutoplay()
      break
  }
}

// Contact landlord
function contactLandlord() {
  const property = viewingProperty.value
  if (!property.contact) {
    showToast('Contact information not available', 'error')
    return
  }
  
  const message = encodeURIComponent(
    `Hi! I'm interested in ${property.title} at ${property.address}. Is it still available?`
  )
  
  let whatsappLink = property.contact
  if (!whatsappLink.includes('?text=')) {
    whatsappLink += `?text=${message}`
  }
  
  window.open(whatsappLink, '_blank')
}

// Share property
async function shareProperty() {
  const property = viewingProperty.value
  const shareUrl = `${window.location.origin}/tenant/properties`
  
  const shareData = {
    title: property.title,
    text: `Check out this property: ${property.title} - RM${property.payment?.amount || 0}/month`,
    url: shareUrl
  }
  
  try {
    if (navigator.share) {
      await navigator.share(shareData)
      showToast('Shared successfully!', 'success')
    } else {
      await navigator.clipboard.writeText(shareUrl)
      showToast('🔗 Link copied to clipboard!', 'success')
    }
  } catch (err) {
    console.error('Share failed:', err)
  }
}

// Lifecycle hooks
onMounted(() => {
  fetchProperties()
  window.addEventListener('keydown', handleKeyPress)
})

onUnmounted(() => {
  stopAutoPlay()
  window.removeEventListener('keydown', handleKeyPress)
  document.body.style.overflow = 'auto'
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root {
  --primary-teal: #14b8a6;
  --deep-teal: #0891b2;
  --dark-teal: #0e7490;
  --light-teal: #ecfeff;
  --very-light-teal: #f0fdfa;
  --mint: #99f6e4;
  --light-mint: #ccfbf1;
  --instagram-red: #ed4956;
  --text-dark: #0e7490;
  --text-muted: #64748b;
  --text-black: #1e293b;
  --white: #FFFFFF;
  --gray-50: #f8fafc;
  --gray-100: #f1f5f9;
  --gray-200: #e2e8f0;
  --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
  --shadow-xl: 0 12px 40px rgba(0, 0, 0, 0.1);
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  background-color: var(--very-light-teal);
  color: var(--text-dark);
  line-height: 1.6;
}

.tenant-browse-container {
  min-height: 100vh;
  background: var(--very-light-teal);
}

/* Hero Header */
.hero-header-tenant {
  position: relative;
  padding: 3.5rem 2rem 6rem;
  overflow: hidden;
  min-height: 480px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 0;
}

.hero-image {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-size: cover;
  background-position: center;
}

.hero-gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(20, 184, 166, 0.65) 0%,
    rgba(6, 182, 212, 0.75) 50%,
    rgba(16, 185, 129, 0.65) 100%
  );
  backdrop-filter: blur(12px) saturate(150%);
}

.hero-content {
  max-width: 900px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  text-align: center;
  width: 100%;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1rem;
  border-radius: 50px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.badge-icon {
  width: 18px;
  height: 18px;
  fill: white;
}

.hero-title {
  font-size: 3.5rem;
  font-weight: 800;
  color: white;
  margin-bottom: 0.75rem;
  letter-spacing: -0.02em;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.hero-subtitle {
  font-size: 1.375rem;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 2.5rem;
  font-weight: 500;
}

/* Search Bar */
.search-bar-tenant {
  background: rgba(255, 255, 255, 0.95);
  border-radius: 50px;
  padding: 0.875rem 1.5rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  max-width: 650px;
  margin: 0 auto 2rem;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  backdrop-filter: blur(20px);
}

.search-icon {
  width: 24px;
  height: 24px;
  fill: var(--primary-teal);
  flex-shrink: 0;
}

.search-input {
  flex: 1;
  background: transparent;
  border: none;
  color: var(--text-dark);
  font-size: 1rem;
  font-weight: 500;
  outline: none;
  font-family: inherit;
}

.search-input::placeholder {
  color: var(--text-muted);
}

/* Quick Filters */
.quick-filters {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  align-items: center;
  padding: 0 1rem;
}

.filter-btn {
  padding: 0.75rem 1.5rem;
  background: rgba(255, 255, 255, 0.35);
  backdrop-filter: blur(10px);
  border: 1.5px solid rgba(255, 255, 255, 0.5);
  border-radius: 50px;
  color: rgba(255, 255, 255, 0.95);
  font-weight: 700;
  font-size: 0.875rem;
  cursor: pointer;
  transition: var(--transition);
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
  letter-spacing: 0.02em;
}

.filter-btn:hover {
  background: rgba(255, 255, 255, 0.5);
  border-color: rgba(255, 255, 255, 0.7);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.filter-btn.active {
  background: white;
  color: var(--primary-teal);
  border-color: white;
  box-shadow: 0 4px 16px rgba(255, 255, 255, 0.4);
  text-shadow: none;
}

/* Main Content */
.main-content-tenant {
  padding: 3rem 2rem 4rem;
  margin-top: 0;
  position: relative;
  z-index: 2;
}

.content-container {
  max-width: 1400px;
  margin: 0 auto;
}

/* State Container */
.state-container {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--white);
  border-radius: 20px;
  box-shadow: var(--shadow-md);
}

/* Loading Spinner */
.loading-spinner-wrapper {
  position: relative;
  width: 120px;
  height: 120px;
  margin: 0 auto 2rem;
}

.loading-spinner-ring {
  position: absolute;
  width: 100%;
  height: 100%;
  border-radius: 50%;
  border: 4px solid transparent;
}

.loading-spinner-ring:nth-child(1) {
  border-top-color: #14b8a6;
  border-right-color: #14b8a6;
  animation: spinFast 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 20px rgba(20, 184, 166, 0.3);
}

.loading-spinner-ring:nth-child(2) {
  width: 85%;
  height: 85%;
  top: 7.5%;
  left: 7.5%;
  border-bottom-color: #0891b2;
  border-left-color: #0891b2;
  animation: spinMedium 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite reverse;
  box-shadow: 0 0 15px rgba(8, 145, 178, 0.3);
}

.loading-spinner-ring:nth-child(3) {
  width: 70%;
  height: 70%;
  top: 15%;
  left: 15%;
  border-top-color: #2dd4bf;
  border-right-color: #2dd4bf;
  animation: spinSlow 2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  box-shadow: 0 0 10px rgba(45, 212, 191, 0.3);
}

@keyframes spinFast {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spinMedium {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes spinSlow {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.loading-text {
  color: #0891b2;
  font-size: 1.125rem;
  font-weight: 600;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.6; }
}

/* Empty State */
.empty-illustration {
  margin-bottom: 2rem;
}

.illustration-svg {
  width: 100px;
  height: 100px;
  margin: 0 auto;
  filter: drop-shadow(0 4px 12px rgba(20, 184, 166, 0.2));
}

.empty-title {
  font-size: 1.625rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.75rem;
}

.empty-description {
  color: var(--text-muted);
  font-size: 1rem;
}

/* Properties Grid */
.properties-grid-tenant {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
}

/* Property Card - Instagram Style */
.property-card-instagram {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: var(--shadow-md);
}

.property-card-instagram:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow-xl);
}

.card-image-instagram {
  position: relative;
  height: 280px;
  overflow: hidden;
  background: var(--light-teal);
}

.card-image-instagram img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.property-card-instagram:hover .card-image-instagram img {
  transform: scale(1.1);
}

.image-count-badge {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(10px);
  padding: 0.5rem 0.875rem;
  border-radius: 50px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.375rem;
  z-index: 2;
}

.image-count-badge .icon {
  width: 16px;
  height: 16px;
  fill: white;
}

.available-badge-tenant {
  position: absolute;
  top: 1rem;
  left: 1rem;
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
  padding: 0.5rem 1rem;
  border-radius: 50px;
  color: white;
  font-size: 0.813rem;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.4);
  z-index: 2;
}

.card-overlay-instagram {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, transparent 60%);
  display: flex;
  align-items: flex-end;
  padding: 1.5rem;
  opacity: 0;
  transition: opacity 0.3s;
}

.property-card-instagram:hover .card-overlay-instagram {
  opacity: 1;
}

.overlay-content h3 {
  color: white;
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.price-large {
  color: white;
  font-size: 1.75rem;
  font-weight: 800;
}

.price-large span {
  font-size: 1rem;
  font-weight: 500;
  opacity: 0.9;
}

.card-info-instagram {
  padding: 1.5rem;
}

.property-title-instagram {
  font-size: 1.125rem;
  font-weight: 700;
  color: var(--text-dark);
  margin-bottom: 0.5rem;
  line-height: 1.4;
}

.property-location-instagram {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.property-location-instagram .icon {
  width: 14px;
  height: 14px;
  fill: var(--text-muted);
}

.card-stats-instagram {
  display: flex;
  gap: 1rem;
  padding: 0.875rem 0;
  border-top: 1px solid var(--light-mint);
  border-bottom: 1px solid var(--light-mint);
  margin-bottom: 1rem;
}

.card-stats-instagram .stat {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  font-size: 0.813rem;
  color: var(--text-dark);
  font-weight: 600;
}

.card-stats-instagram .stat .icon {
  width: 16px;
  height: 16px;
  fill: var(--primary-teal);
}

.card-price-instagram {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
}

.card-price-instagram .amount {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--primary-teal);
}

.card-price-instagram .period {
  font-size: 0.875rem;
  color: var(--text-muted);
  font-weight: 500;
}

/* ============================================
   REDESIGNED PROPERTY MODAL - MODERN LAYOUT
   ============================================ */

.property-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.75);
  z-index: 2000;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  animation: fadeIn 0.3s ease;
  overflow-y: auto;
}

.property-modal-modern {
  width: 100%;
  max-width: 1400px;
  max-height: 95vh;
  background: white;
  position: relative;
  display: flex;
  flex-direction: column;
  border-radius: 16px;
  overflow: hidden;
  margin: 2rem;
  animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Close Button */
.modal-close-modern {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(10px);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  z-index: 100;
  transition: var(--transition);
}

.modal-close-modern:hover {
  background: rgba(0, 0, 0, 0.7);
  transform: scale(1.1);
}

.modal-close-modern .icon {
  width: 24px;
  height: 24px;
  fill: white;
}

/* Quick Actions (Top Right) */
.quick-actions-hero {
  position: absolute;
  top: 1.5rem;
  right: 5rem;
  display: flex;
  gap: 0.75rem;
  z-index: 100;
}

.action-icon-btn {
  width: 44px;
  height: 44px;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(10px);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
}

.action-icon-btn:hover {
  background: rgba(0, 0, 0, 0.7);
  transform: scale(1.1);
}

.action-icon-btn.saved {
  background: var(--instagram-red);
}

.action-icon-btn .icon {
  width: 20px;
  height: 20px;
  fill: white;
}

/* Modal Content Wrapper */
.modal-content-wrapper {
  display: flex;
  flex-direction: column;
  overflow-y: auto;
  max-height: 95vh;
}

/* HERO GALLERY SECTION */
.hero-gallery-section {
  position: relative;
  width: 100%;
  background: #000;
}

/* Image Category Pills (above hero) */
.image-category-pills {
  display: flex;
  gap: 0.5rem;
  padding: 1rem 1.5rem;
  background: rgba(0, 0, 0, 0.7);
  backdrop-filter: blur(10px);
  overflow-x: auto;
  flex-wrap: wrap;
}

.category-pill {
  padding: 0.5rem 1rem;
  background: rgba(255, 255, 255, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 50px;
  color: white;
  font-weight: 600;
  font-size: 0.813rem;
  cursor: pointer;
  transition: var(--transition);
  white-space: nowrap;
}

.category-pill:hover {
  background: rgba(255, 255, 255, 0.25);
}

.category-pill.active {
  background: white;
  color: var(--primary-teal);
  border-color: white;
}

.pill-count {
  opacity: 0.7;
  margin-left: 0.25rem;
}

/* Hero Image Container */
.hero-image-container {
  position: relative;
  width: 100%;
  height: 500px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #000;
}

.hero-image-main {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  user-select: none;
}

/* Hero Navigation Arrows */
.hero-nav-arrow {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  z-index: 10;
}

.hero-nav-arrow:hover {
  background: rgba(255, 255, 255, 0.5);
  transform: translateY(-50%) scale(1.1);
}

.hero-nav-arrow.left {
  left: 1.5rem;
}

.hero-nav-arrow.right {
  right: 1.5rem;
}

.hero-nav-arrow .icon {
  width: 24px;
  height: 24px;
  fill: white;
}

/* Hero Image Counter */
.hero-image-counter {
  position: absolute;
  bottom: 1.5rem;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(0, 0, 0, 0.75);
  backdrop-filter: blur(10px);
  padding: 0.5rem 1rem;
  border-radius: 50px;
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  z-index: 10;
}

/* Hero Autoplay Toggle */
.hero-autoplay-toggle {
  position: absolute;
  bottom: 1.5rem;
  right: 1.5rem;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  backdrop-filter: blur(10px);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  z-index: 10;
}

.hero-autoplay-toggle:hover {
  background: rgba(255, 255, 255, 0.5);
}

.hero-autoplay-toggle .icon {
  width: 20px;
  height: 20px;
  fill: white;
}

/* Thumbnail Strip */
.thumbnail-strip {
  display: flex;
  gap: 0.5rem;
  padding: 1rem 1.5rem;
  background: rgba(0, 0, 0, 0.7);
  overflow-x: auto;
}

.thumbnail-item {
  flex-shrink: 0;
  width: 80px;
  height: 60px;
  border-radius: 8px;
  overflow: hidden;
  border: 2px solid transparent;
  cursor: pointer;
  transition: var(--transition);
  background: #000;
}

.thumbnail-item:hover {
  border-color: rgba(255, 255, 255, 0.5);
}

.thumbnail-item.active {
  border-color: white;
}

.thumbnail-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.thumbnail-more {
  flex-shrink: 0;
  width: 80px;
  height: 60px;
  border-radius: 8px;
  background: rgba(255, 255, 255, 0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 0.875rem;
}

/* TWO-COLUMN CONTENT SECTION */
.content-two-column {
  display: grid;
  grid-template-columns: 1fr 380px;
  gap: 3rem;
  padding: 3rem;
  background: var(--white);
}

/* LEFT COLUMN: Description & Details */
.content-left-column {
  display: flex;
  flex-direction: column;
  gap: 2.5rem;
}

/* ===================================
   REDESIGNED PROPERTY HEADER - TITLE & LOCATION
   =================================== */

.property-header-redesigned {
  border-bottom: 3px solid #E0FFFF; /* Very Light Cyan divider */
  padding-bottom: 2rem;
  margin-bottom: 2rem;
}

/* Property Title - Maximum Impact */
.property-title-redesigned {
  font-size: 2.5rem; /* 40px - Large and impactful */
  font-weight: 900; /* Extra Bold/Black */
  color: #333333; /* Primary Text Color - High Contrast */
  margin: 0 0 1rem 0;
  line-height: 1.2;
  letter-spacing: -0.02em;
  text-transform: none;
  word-wrap: break-word;
}

/* Property Location Container */
.property-location-redesigned {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  margin-top: 0.75rem;
}

/* Location Pin Icon */
.location-pin-icon-redesigned {
  width: 22px;
  height: 22px;
  fill: #008080; /* Deep Teal */
  flex-shrink: 0;
  animation: pulse-pin 2s ease-in-out infinite;
}

@keyframes pulse-pin {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.1);
  }
}

/* Location Text */
.location-text-redesigned {
  font-size: 1.125rem; /* 18px - Secondary but readable */
  font-weight: 500; /* Regular/Medium weight */
  color: #008080; /* Deep Teal accent */
  line-height: 1.5;
  letter-spacing: 0.01em;
}

/* Hover effect for location */
.property-location-redesigned:hover .location-pin-icon-redesigned {
  fill: #40E0D0; /* Bright Turquoise on hover */
  animation: bounce-pin 0.6s ease;
}

@keyframes bounce-pin {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-4px);
  }
}

.property-location-redesigned:hover .location-text-redesigned {
  color: #40E0D0; /* Bright Turquoise on hover */
}

/* Responsive - Property Header */
@media (max-width: 1024px) {
  .property-title-redesigned {
    font-size: 2.25rem; /* 36px */
  }

  .location-text-redesigned {
    font-size: 1.063rem; /* 17px */
  }

  .location-pin-icon-redesigned {
    width: 20px;
    height: 20px;
  }
}

@media (max-width: 768px) {
  .property-header-redesigned {
    padding-bottom: 1.5rem;
    margin-bottom: 1.5rem;
  }

  .property-title-redesigned {
    font-size: 2rem; /* 32px - Minimum size */
    line-height: 1.25;
  }

  .location-text-redesigned {
    font-size: 1rem; /* 16px */
  }

  .location-pin-icon-redesigned {
    width: 18px;
    height: 18px;
  }
}

@media (max-width: 480px) {
  .property-title-redesigned {
    font-size: 1.75rem; /* 28px for very small screens */
    letter-spacing: -0.01em;
  }

  .location-text-redesigned {
    font-size: 0.938rem; /* 15px */
  }

  .property-location-redesigned {
    gap: 0.5rem;
  }
}

/* Section Titles */
.section-title-modern {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-black);
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

/* Description Section */
.description-section {
  line-height: 1.8;
}

.description-text {
  color: var(--text-muted);
  font-size: 0.938rem;
}

/* ===================================
   REDESIGNED AMENITIES SECTION - ICON GRID
   =================================== */

.amenities-grid-redesigned {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
}

.amenity-card-redesigned {
  background: #E0FFFF; /* Very Light Cyan */
  border: 2px solid #B0E5E5;
  border-radius: 12px;
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: default;
  opacity: 0;
  animation: fadeInUp 0.5s ease forwards;
}

.amenity-card-redesigned:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 16px rgba(0, 128, 128, 0.15);
  border-color: #40E0D0; /* Bright Turquoise */
  background: rgba(224, 255, 255, 0.8);
}

.amenity-icon-wrapper-redesigned {
  width: 36px;
  height: 36px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0, 128, 128, 0.1);
  transition: all 0.3s ease;
}

.amenity-card-redesigned:hover .amenity-icon-wrapper-redesigned {
  background: #008080; /* Deep Teal */
  transform: scale(1.1) rotate(5deg);
  box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
}

.amenity-check-icon-redesigned {
  width: 20px;
  height: 20px;
  fill: #008080; /* Deep Teal */
  transition: fill 0.3s ease;
}

.amenity-card-redesigned:hover .amenity-check-icon-redesigned {
  fill: white;
}

.amenity-text-redesigned {
  font-size: 0.938rem;
  font-weight: 600;
  color: #333333; /* Primary Text */
  line-height: 1.4;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ===================================
   REDESIGNED LOCATION SECTION - WITH ICONS
   =================================== */

.location-info-grid-redesigned {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.location-card-redesigned {
  background: #E0FFFF; /* Very Light Cyan */
  border: 2px solid #B0E5E5;
  border-radius: 12px;
  padding: 1.25rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.location-card-redesigned:hover {
  transform: translateX(6px);
  box-shadow: 0 6px 16px rgba(0, 128, 128, 0.15);
  border-color: #40E0D0; /* Bright Turquoise */
  background: rgba(224, 255, 255, 0.8);
}

.location-icon-circle-redesigned {
  width: 48px;
  height: 48px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0, 128, 128, 0.1);
  transition: all 0.3s ease;
}

.location-card-redesigned:hover .location-icon-circle-redesigned {
  background: #008080; /* Deep Teal */
  transform: scale(1.1) rotate(5deg);
  box-shadow: 0 4px 12px rgba(0, 128, 128, 0.2);
}

.location-icon-redesigned {
  width: 24px;
  height: 24px;
  fill: #008080; /* Deep Teal */
  transition: fill 0.3s ease;
}

.location-card-redesigned:hover .location-icon-redesigned {
  fill: white;
}

.location-content-redesigned {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
  flex: 1;
}

.location-label-redesigned {
  font-size: 0.875rem;
  font-weight: 700;
  color: #008080; /* Deep Teal */
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.location-value-redesigned {
  font-size: 1rem;
  font-weight: 600;
  color: #333333; /* Primary Text */
  line-height: 1.5;
}

/* Location Advantages Box */
.location-advantages-box-redesigned {
  background: linear-gradient(135deg, #E0FFFF, #F0FFFF);
  border: 2px solid #40E0D0; /* Bright Turquoise */
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  box-shadow: 0 4px 12px rgba(64, 224, 208, 0.15);
}

.advantage-icon-redesigned {
  width: 28px;
  height: 28px;
  fill: #40E0D0; /* Bright Turquoise */
  flex-shrink: 0;
  margin-top: 0.125rem;
}

.location-advantages-text-redesigned {
  font-size: 0.938rem;
  color: #333333; /* Primary Text */
  line-height: 1.7;
  font-weight: 500;
  margin: 0;
}

/* Responsive - Amenities & Location */
@media (max-width: 768px) {
  .amenities-grid-redesigned {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.875rem;
  }

  .amenity-card-redesigned {
    padding: 0.875rem 1rem;
  }

  .amenity-icon-wrapper-redesigned {
    width: 32px;
    height: 32px;
  }

  .amenity-check-icon-redesigned {
    width: 18px;
    height: 18px;
  }

  .amenity-text-redesigned {
    font-size: 0.875rem;
  }

  .location-card-redesigned {
    padding: 1rem;
  }

  .location-icon-circle-redesigned {
    width: 42px;
    height: 42px;
  }

  .location-icon-redesigned {
    width: 22px;
    height: 22px;
  }
}

@media (max-width: 480px) {
  .amenities-grid-redesigned {
    grid-template-columns: 1fr;
  }

  .location-advantages-box-redesigned {
    flex-direction: column;
    padding: 1.25rem;
  }

  .advantage-icon-redesigned {
    width: 24px;
    height: 24px;
  }
}

/* ===================================
   REDESIGNED CONTRACT SECTION
   =================================== */

.contract-card-redesigned {
  background: #E0FFFF; /* Very Light Cyan */
  border: 2px solid #B0E5E5;
  border-radius: 12px;
  padding: 1.5rem;
  display: flex;
  align-items: center;
  gap: 1.25rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 2px 8px rgba(0, 128, 128, 0.08);
}

.contract-card-redesigned:hover {
  transform: translateX(6px);
  box-shadow: 0 6px 16px rgba(0, 128, 128, 0.15);
  border-color: #40E0D0; /* Bright Turquoise */
  background: rgba(224, 255, 255, 0.8);
}

.contract-icon-circle-redesigned {
  width: 52px;
  height: 52px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 10px rgba(0, 128, 128, 0.12);
  transition: all 0.3s ease;
}

.contract-card-redesigned:hover .contract-icon-circle-redesigned {
  background: #008080; /* Deep Teal */
  transform: scale(1.1) rotate(5deg);
  box-shadow: 0 4px 14px rgba(0, 128, 128, 0.25);
}

.contract-icon-redesigned {
  width: 28px;
  height: 28px;
  fill: #008080; /* Deep Teal */
  transition: fill 0.3s ease;
}

.contract-card-redesigned:hover .contract-icon-redesigned {
  fill: white;
}

.contract-content-redesigned {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  flex: 1;
}

.contract-label-redesigned {
  font-size: 0.875rem;
  font-weight: 700;
  color: #008080; /* Deep Teal */
  text-transform: uppercase;
  letter-spacing: 0.5px;
  line-height: 1.2;
}

.contract-value-redesigned {
  font-size: 1.375rem;
  font-weight: 800;
  color: #008080; /* Deep Teal */
  line-height: 1.3;
  letter-spacing: -0.01em;
  transition: all 0.3s ease;
}

.contract-card-redesigned:hover .contract-value-redesigned {
  color: #40E0D0; /* Bright Turquoise on hover */
  transform: translateX(4px);
}

/* Optional: Add a secondary info row if needed */
.contract-secondary-info-redesigned {
  font-size: 0.813rem;
  color: #666;
  font-weight: 500;
  margin-top: 0.25rem;
  line-height: 1.4;
}

/* Responsive - Contract Section */
@media (max-width: 768px) {
  .contract-card-redesigned {
    padding: 1.25rem;
  }

  .contract-icon-circle-redesigned {
    width: 46px;
    height: 46px;
  }

  .contract-icon-redesigned {
    width: 24px;
    height: 24px;
  }

  .contract-value-redesigned {
    font-size: 1.25rem;
  }
}

@media (max-width: 480px) {
  .contract-card-redesigned {
    flex-direction: column;
    text-align: center;
    padding: 1.5rem 1rem;
  }

  .contract-content-redesigned {
    align-items: center;
  }

  .contract-value-redesigned {
    font-size: 1.125rem;
  }
}

/* ===================================
   REDESIGNED AVAILABLE ROOMS SECTION
   =================================== */

.rooms-grid-redesigned {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.75rem;
}

.room-card-redesigned {
  background: #E0FFFF; /* Very Light Cyan - Card Background */
  border-radius: 16px;
  padding: 1.75rem;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #B0E5E5; /* Slightly darker cyan for subtle border */
  box-shadow: 0 4px 12px rgba(0, 128, 128, 0.1); /* Soft teal shadow */
  position: relative;
  overflow: hidden;
}

/* Hover State */
.room-card-redesigned:hover {
  transform: translateY(-6px) scale(1.02);
  box-shadow: 0 12px 32px rgba(0, 128, 128, 0.2);
  border-color: #40E0D0; /* Bright Turquoise on hover */
}

/* Room Header */
.room-card-header-redesigned {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #B0E5E5;
}

.room-title-redesigned {
  font-size: 1.375rem;
  font-weight: 800;
  color: #008080; /* Deep Teal */
  margin: 0;
  letter-spacing: -0.01em;
}

.room-price-redesigned {
  font-size: 1.25rem;
  font-weight: 700;
  color: #40E0D0; /* Bright Turquoise */
  padding: 0.375rem 0.875rem;
  background: rgba(64, 224, 208, 0.15);
  border-radius: 50px;
}

/* Room Details */
.room-details-redesigned {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.room-detail-row-redesigned {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.625rem;
  background: rgba(255, 255, 255, 0.6);
  border-radius: 10px;
  transition: all 0.3s ease;
}

.room-card-redesigned:hover .room-detail-row-redesigned {
  background: rgba(255, 255, 255, 0.85);
}

.room-icon-redesigned {
  width: 22px;
  height: 22px;
  fill: #008080; /* Deep Teal */
  flex-shrink: 0;
}

.room-detail-text-redesigned {
  font-size: 0.938rem;
  color: #555;
  font-weight: 500;
}

.room-detail-text-redesigned strong {
  color: #008080; /* Deep Teal */
  font-weight: 700;
}

/* CTA Button */
.room-cta-button-redesigned {
  width: 100%;
  padding: 0.875rem 1.25rem;
  background: #40E0D0; /* Bright Turquoise */
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.938rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.625rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 12px rgba(64, 224, 208, 0.3);
}

.room-cta-button-redesigned:hover {
  background: #2DC2B2; /* Slightly darker turquoise */
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(64, 224, 208, 0.4);
}

.cta-icon-redesigned {
  width: 18px;
  height: 18px;
  fill: white;
}

/* ===================================
   REDESIGNED COMMON AREAS SECTION
   =================================== */

.common-areas-grid-redesigned {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 2rem;
}

.common-area-card-redesigned {
  background: #E0FFFF; /* Very Light Cyan */
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  border: 2px solid #B0E5E5;
  box-shadow: 0 4px 12px rgba(0, 128, 128, 0.1);
}

.common-area-card-redesigned:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 12px 32px rgba(0, 128, 128, 0.2);
  border-color: #40E0D0; /* Bright Turquoise */
}

/* Image Wrapper */
.common-area-image-wrapper-redesigned {
  position: relative;
  width: 100%;
  height: 200px; /* Increased from 120px */
  overflow: hidden;
  background: #f0f0f0;
}

.common-area-image-redesigned {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.common-area-card-redesigned:hover .common-area-image-redesigned {
  transform: scale(1.15);
}

/* Overlay on Hover */
.common-area-overlay-redesigned {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to top, rgba(64, 224, 208, 0.95) 0%, rgba(0, 128, 128, 0.7) 100%);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.common-area-card-redesigned:hover .common-area-overlay-redesigned {
  opacity: 1;
}

.overlay-icon-redesigned {
  width: 40px;
  height: 40px;
  fill: white;
  animation: bounce 1s ease-in-out infinite;
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-8px);
  }
}

.overlay-text-redesigned {
  color: white;
  font-weight: 700;
  font-size: 0.938rem;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Label Section */
.common-area-label-redesigned {
  padding: 1.25rem;
  background: white;
  text-align: center;
}

.common-area-name-redesigned {
  font-size: 1rem;
  font-weight: 700;
  color: #008080; /* Deep Teal */
  display: block;
}


/* =========================================
   REDESIGNED RIGHT SIDEBAR - HIGH CONTRAST
   ========================================= */

.content-right-sidebar {
  position: relative;
}

.sidebar-sticky-container {
  position: sticky;
  top: 2rem;
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

/* CONSOLIDATED BOOKING SUMMARY BOX */
.booking-summary-box-redesigned {
  background: white;
  padding: 2rem;
  border-radius: 16px;
  border: 2px solid #D0D0D0;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

/* Price Section */
.price-section-redesigned {
  margin-bottom: 1.5rem;
}

.price-label-redesigned {
  font-size: 0.875rem;
  color: #666;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
}

.price-main-redesigned {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
}

.price-amount-redesigned {
  font-size: 2.75rem;
  font-weight: 800;
  color: #008080; /* Deep Teal - HIGH CONTRAST */
  line-height: 1;
  letter-spacing: -0.02em;
}

.price-period-redesigned {
  font-size: 1.125rem;
  color: #666;
  font-weight: 600;
}

/* Divider */
.summary-divider-redesigned {
  height: 1px;
  background: linear-gradient(to right, transparent, #D0D0D0, transparent);
  margin: 1.5rem 0;
}

/* Payment Details */
.payment-details-redesigned {
  display: flex;
  flex-direction: column;
  gap: 0.875rem;
  margin-bottom: 1.5rem;
}

.payment-row-redesigned {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: #F8F8F8;
  border-radius: 10px;
  transition: all 0.3s ease;
}

.payment-row-redesigned:hover {
  background: #F0F0F0;
}

.payment-label-redesigned {
  font-size: 0.938rem;
  color: #666;
  font-weight: 500;
}

.payment-value-redesigned {
  font-size: 1rem;
  color: #333333; /* Primary Text - High Contrast */
  font-weight: 700;
}

/* Key Stats Box with Background */
.key-stats-box-redesigned {
  background: #E0FFFF; /* Very Light Cyan */
  padding: 1.5rem;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
  border: 1px solid #B0E5E5;
}

.stat-item-redesigned {
  display: flex;
  align-items: center;
  gap: 1rem;
  transition: all 0.3s ease;
}

.stat-item-redesigned:hover {
  transform: translateX(4px);
}

.stat-icon-circle-redesigned {
  width: 48px;
  height: 48px;
  background: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  box-shadow: 0 2px 8px rgba(0, 128, 128, 0.15);
  transition: all 0.3s ease;
}

.stat-item-redesigned:hover .stat-icon-circle-redesigned {
  background: #008080;
  transform: rotate(5deg) scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 128, 128, 0.3);
}

.stat-icon-redesigned {
  width: 24px;
  height: 24px;
  fill: #008080; /* Deep Teal */
  transition: fill 0.3s ease;
}

.stat-item-redesigned:hover .stat-icon-redesigned {
  fill: white;
}

.stat-content-redesigned {
  display: flex;
  flex-direction: column;
  gap: 0.125rem;
}

.stat-number-redesigned {
  font-size: 1.75rem;
  font-weight: 800;
  color: #008080; /* Deep Teal */
  line-height: 1;
  font-variant-numeric: tabular-nums;
  transition: all 0.3s ease;
}

.stat-item-redesigned:hover .stat-number-redesigned {
  color: #40E0D0; /* Bright Turquoise on hover */
  transform: scale(1.05);
}

.stat-label-redesigned {
  font-size: 0.813rem;
  color: #666;
  font-weight: 600;
}

/* WhatsApp CTA Button */
.btn-whatsapp-redesigned {
  width: 100%;
  padding: 1.25rem;
  background: #25D366; /* WhatsApp Green - Better contrast */
  color: #FFFFFF; /* Pure white text */
  border: none;
  border-radius: 14px;
  font-weight: 800; /* Increased from 700 for better visibility */
  font-size: 1.063rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
  position: relative;
  overflow: hidden;
  text-transform: uppercase;
  letter-spacing: 1px; /* Increased spacing for readability */
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2); /* Added text shadow for contrast */
}

.btn-whatsapp-redesigned::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.btn-whatsapp-redesigned:active::before {
  width: 300px;
  height: 300px;
}

.btn-whatsapp-redesigned:hover {
  background: #1EBE57; /* Darker green on hover */
  transform: translateY(-3px);
  box-shadow: 0 10px 28px rgba(37, 211, 102, 0.5);
}

.whatsapp-icon-redesigned {
  width: 22px;
  height: 22px;
  fill: white;
  position: relative;
  z-index: 1;
  animation: wiggle-icon 1.5s ease-in-out infinite;
  filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2)); /* Added shadow to icon */
}

@keyframes wiggle-icon {
  0%, 100% { transform: rotate(0deg); }
  10%, 30% { transform: rotate(-4deg); }
  20%, 40% { transform: rotate(4deg); }
}


/* Response Time */
.response-time-redesigned {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  font-size: 0.875rem;
  color: #666;
  font-weight: 500;
  padding: 0.75rem;
  background: #F8F8F8;
  border-radius: 10px;
}

.clock-icon-redesigned {
  width: 16px;
  height: 16px;
  fill: #008080; /* Deep Teal */
}

/* Responsive - Redesigned Sidebar */
@media (max-width: 1200px) {
  .content-right-sidebar {
    order: -1;
  }

  .sidebar-sticky-container {
    position: relative;
    top: 0;
  }

  .booking-summary-box-redesigned {
    padding: 1.5rem;
  }

  .key-stats-box-redesigned {
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-around;
  }

  .stat-item-redesigned {
    flex: 1;
    min-width: 150px;
  }
}

@media (max-width: 768px) {
  .price-amount-redesigned {
    font-size: 2.25rem;
  }

  .stat-number-redesigned {
    font-size: 1.5rem;
  }

  .btn-whatsapp-redesigned {
    padding: 1rem;
    font-size: 0.938rem;
  }
}

@media (max-width: 480px) {
  .key-stats-box-redesigned {
    flex-direction: column;
  }

  .stat-item-redesigned {
    width: 100%;
  }
}

/* Toast Notification */
.toast-notification {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem 1.5rem;
  background: white;
  border-radius: 12px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  border-left: 4px solid var(--primary-teal);
  z-index: 3000;
  min-width: 320px;
  animation: slideInToast 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInToast {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.toast-notification.success {
  border-left-color: var(--primary-teal);
}

.toast-notification.error {
  border-left-color: #ef4444;
}

.toast-notification.info {
  border-left-color: #3b82f6;
}

.toast-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
}

.toast-icon .icon {
  width: 100%;
  height: 100%;
  fill: var(--primary-teal);
}

.toast-message {
  color: var(--text-dark);
  font-weight: 600;
  font-size: 0.938rem;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-fade-enter-from,
.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

.icon {
  fill: currentColor;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .content-two-column {
    grid-template-columns: 1fr;
  }

  .content-right-sidebar {
    order: -1;
  }

  .sidebar-sticky-container {
    position: relative;
    top: 0;
  }
}

@media (max-width: 768px) {
  .hero-header-tenant {
    padding: 2.5rem 1.5rem 4rem;
    min-height: 400px;
  }

  .hero-title {
    font-size: 2.5rem;
  }

  .hero-subtitle {
    font-size: 1.125rem;
  }

  .search-bar-tenant {
    max-width: 100%;
  }

  .quick-filters {
    padding: 0 1rem;
  }

  .main-content-tenant {
    padding: 2rem 1rem 3rem;
  }

  .properties-grid-tenant {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }

  .property-modal-modern {
    margin: 0;
    max-height: 100vh;
    border-radius: 0;
  }

  .hero-image-container {
    height: 350px;
  }

  .content-two-column {
    grid-template-columns: 1fr;
    padding: 2rem 1.5rem;
    gap: 2rem;
  }

  .property-title-modern {
    font-size: 1.5rem;
  }

  .rooms-grid-modern {
    grid-template-columns: 1fr;
  }

  .common-areas-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .hero-nav-arrow {
    width: 40px;
    height: 40px;
  }

  .hero-nav-arrow.left {
    left: 1rem;
  }

  .hero-nav-arrow.right {
    right: 1rem;
  }

  .toast-notification {
    bottom: 1rem;
    right: 1rem;
    left: 1rem;
    min-width: auto;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 2rem;
  }

  .card-stats-instagram {
    flex-wrap: wrap;
  }

  .price-amount {
    font-size: 2rem;
  }

  .spec-item-modern strong {
    font-size: 1.25rem;
  }
}

.spec-icon-wrapper {
  width: 48px;
  height: 48px;
  background: linear-gradient(135deg, var(--very-light-teal), var(--light-mint));
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  flex-shrink: 0;
}

.spec-animated:hover .spec-icon-wrapper {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  transform: rotate(5deg) scale(1.15);
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
}

.spec-icon-wrapper .icon {
  width: 24px;
  height: 24px;
  fill: var(--primary-teal);
  transition: all 0.3s ease;
}

.spec-animated:hover .spec-icon-wrapper .icon {
  fill: white;
  transform: scale(1.1);
}

.counting-number {
  font-variant-numeric: tabular-nums;
  transition: all 0.3s ease;
}

.spec-animated:hover .counting-number {
  color: var(--primary-teal);
  transform: scale(1.1);
}

/* Image loading skeleton */
.common-area-card img,
.room-card-modern img {
  background: linear-gradient(
    90deg,
    var(--gray-200) 0%,
    var(--gray-100) 50%,
    var(--gray-200) 100%
  );
  background-size: 200% 100%;
  animation: loading-skeleton 1.5s ease-in-out infinite;
}

@keyframes loading-skeleton {
  0% {
    background-position: 200% 0;
  }
  100% {
    background-position: -200% 0;
  }
}

/* Stop animation when image loads */
.common-area-card img[src],
.room-card-modern img[src] {
  animation: none;
  background: transparent;
}

/* Responsive - Redesigned Sections */
@media (max-width: 768px) {
  .rooms-grid-redesigned {
    grid-template-columns: 1fr;
    gap: 1.25rem;
  }

  .common-areas-grid-redesigned {
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
  }

  .room-card-redesigned {
    padding: 1.25rem;
  }

  .room-title-redesigned {
    font-size: 1.125rem;
  }

  .common-area-image-wrapper-redesigned {
    height: 160px;
  }
}

@media (max-width: 480px) {
  .common-areas-grid-redesigned {
    grid-template-columns: 1fr;
  }

  .room-card-header-redesigned {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.75rem;
  }
}

/* Heart Button on Grid Cards */
.favorite-heart-btn-grid {
  position: absolute;
  top: 1rem;
  left: 1rem;  /* Changed from right to left */
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 5;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.favorite-heart-btn-grid:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.favorite-heart-btn-grid .icon {
  width: 20px;
  height: 20px;
  fill: #cbd5e1;
  transition: all 0.3s;
}

.favorite-heart-btn-grid.active {
  background: #ef4444;
  animation: heartPulse 0.4s ease;
}

.favorite-heart-btn-grid.active .icon {
  fill: white;
}

@keyframes heartPulse {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.2);
  }
}
</style>