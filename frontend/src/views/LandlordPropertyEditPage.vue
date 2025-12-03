<template>
  <div class="dashboard-container">
    
    <div class="header-nav-wrapper">
      <HeaderNav :user="user" />
    </div>

    <!-- Hero Header Section with Background Image + Gradient Overlay -->
    <header class="hero-header">
      <div class="hero-background">
        <div class="hero-image" style="background-image: url('https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&q=80')"></div>
        <div class="hero-gradient-overlay"></div>
      </div>
      
      <div class="hero-content">
        <div class="hero-badge">
          <svg class="badge-icon" viewBox="0 0 24 24">
            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
          </svg>
          <span>Property Management</span>
        </div>
        <h1 class="hero-title">Manage Your Properties</h1>
        <p class="hero-subtitle">View, edit and manage your listings with ease</p>
        
        <!-- Stats Bar -->
        <div class="stats-bar">
          <div class="stat-item">
            <span class="stat-number">{{ properties.length }}</span>
            <span class="stat-label">Total Properties</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">{{ properties.filter(p => p.available_rooms > 0).length }}</span>
            <span class="stat-label">Available</span>
          </div>
          <div class="stat-divider"></div>
          <div class="stat-item">
            <span class="stat-number">{{ properties.reduce((sum, p) => sum + (p.available_rooms || 0), 0) }}</span>
            <span class="stat-label">Total Rooms</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content with Centered Container -->
    <main class="main-content">
      <div class="content-container">
        
        <!-- Dark Turquoise Search Bar -->
        <div class="search-section">
          <div class="search-bar-container">
            <svg class="search-icon" viewBox="0 0 24 24">
              <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
            </svg>
            <input 
              type="text" 
              placeholder="Search by property name, address, or location..." 
              class="search-input" 
              v-model="searchQuery"
            >
          </div>
        </div>

        <!-- Properties Grid or Empty State -->
        <div class="properties-section">
         <!-- Loading State -->
<div v-if="loading" class="state-container">
  <div class="loading-spinner-wrapper">
    <div class="loading-spinner-ring"></div>
    <div class="loading-spinner-ring"></div>
    <div class="loading-spinner-ring"></div>
  </div>
  <p class="loading-text">Loading your properties...</p>
</div>

          <!-- Empty State -->
          <div v-else-if="properties.length === 0" class="state-container">
            <div class="empty-illustration">
              <svg viewBox="0 0 200 200" class="illustration-svg">
                <circle cx="100" cy="100" r="80" fill="#E8F8F5"/>
                <path fill="#1ABC9C" d="M100,40 L140,70 L140,130 L100,160 L60,130 L60,70 Z"/>
                <path fill="#16A085" d="M100,50 L130,70 L130,120 L100,140 L70,120 L70,70 Z"/>
                <circle cx="100" cy="95" r="15" fill="#fff"/>
              </svg>
            </div>
            <h3 class="empty-title">No Properties Yet</h3>
            <p class="empty-description">Start by adding your first property to manage listings and connect with tenants</p>
            <button class="btn-primary btn-large" @click="showAddPropertyModal = true">
              <svg class="icon" viewBox="0 0 24 24">
                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
              </svg>
              Add Your First Property
            </button>
          </div>

          <!-- Properties Grid -->
          <div v-else class="properties-grid">
            <div
              v-for="property in filteredProperties"
              :key="property.id"
              class="property-card-wrapper"
            >
              <div class="turquoise-glow-border" @click="openProperty(property)">
                <div class="property-card">
                  <!-- Image Carousel -->
                  <div class="card-image-section">
                    <img 
                      :src="getImage(property, imageIndexes[property.id])" 
                      :alt="property.title" 
                      class="card-image"
                    >
                    
                    <!-- Availability Badge -->
                    <div class="availability-badge" :class="{ available: property.available_rooms > 0 }">
                      <span v-if="property.available_rooms > 0">Available</span>
                      <span v-else>Fully Occupied</span>
                    </div>

                    <!-- Carousel Controls -->
                    <div class="carousel-overlay">
                      <button 
                        class="carousel-button prev" 
                        @click.stop="prevImage(property.id)"
                        v-if="(property.imagesArray || []).length > 1"
                      >
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                        </svg>
                      </button>
                      <button 
                        class="carousel-button next" 
                        @click.stop="nextImage(property.id)"
                        v-if="(property.imagesArray || []).length > 1"
                      >
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                      </button>
                    </div>

                    <!-- Image Dots -->
                    <div class="image-dots" v-if="(property.imagesArray || []).length > 1">
                      <span
                        v-for="(img, idx) in property.imagesArray"
                        :key="idx"
                        class="dot"
                        :class="{ active: imageIndexes[property.id] === idx }"
                      ></span>
                    </div>
                  </div>

                  <!-- Card Content -->
                  <div class="card-content">
                    <div class="card-header">
                      <h3 class="property-name">{{ property.title }}</h3>
                      <button class="quick-edit" @click.stop="openProperty(property)">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                        </svg>
                      </button>
                    </div>

                    <p class="property-location">
                      <svg class="location-icon" viewBox="0 0 24 24">
                        <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                      </svg>
                      {{ property.address || 'Location not specified' }}
                    </p>

                    <!-- Property Features -->
                    <div class="property-features">
                      <div class="feature">
                        <svg class="feature-icon" viewBox="0 0 24 24">
                          <path d="M17 11V3H7v4H3v14h8v-4h2v4h8V11h-4zM7 19H5v-2h2v2zm0-4H5v-2h2v2zm0-4H5V9h2v2zm4 4H9v-2h2v2zm0-4H9V9h2v2zm0-4H9V5h2v2zm4 8h-2v-2h2v2zm0-4h-2V9h2v2zm0-4h-2V5h2v2zm4 12h-2v-2h2v2zm0-4h-2v-2h2v2z"/>
                        </svg>
                        <span>{{ property.bedrooms || property.available_rooms || 0 }} Beds</span>
                      </div>
                      <div class="feature">
                        <svg class="feature-icon" viewBox="0 0 24 24">
                          <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.1h-15V5h15v14.1zm0-16.1h-15c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h15c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
                        </svg>
                        <span>{{ property.total_bathrooms || property.bathrooms || 0 }} Baths</span>
                      </div>
                      <div class="feature">
                        <svg class="feature-icon" viewBox="0 0 24 24">
                          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span>{{ property.current_tenants || 0 }}/{{ property.tenants_needed || 0 }} Tenants</span>
                      </div>
                    </div>

                    <!-- Pricing Section -->
                    <div class="pricing-section">
                      <div class="price-main">
                        <span class="price-amount">RM {{ formatNumber(property.payment?.amount || 0) }}</span>
                        <span class="price-period">/ month</span>
                      </div>
                      <div class="price-details">
                        <span class="deposit-label">Deposit:</span>
                        <span class="deposit-amount">RM {{ formatNumber(property.payment?.deposit || 0) }}</span>
                      </div>
                    </div>

                    <!-- Action Footer -->
                    <div class="card-footer">
                      <button class="btn-view" @click.stop="openProperty(property)">
                        View Details
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Floating Action Button -->
    <button class="fab" @click="showAddPropertyModal = true" title="Add new property">
      <svg class="icon" viewBox="0 0 24 24">
        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
      </svg>
      <span class="fab-label">Add Property</span>
    </button>

    <!-- Property Edit Modal - Main Modal -->
    <div v-if="selectedProperty" class="modal-overlay-modern" @click="closeModal">
      <div class="modal-wizard" @click.stop>
        <!-- Close Button -->
        <button class="modal-close-modern" @click="closeModal">
          <svg class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>

        <!-- Progress Bar at Top -->
        <div class="modal-progress-bar">
          <div class="progress-track">
            <div class="progress-fill" :style="{ width: `${(currentStep / steps.length) * 100}%` }"></div>
          </div>
          <div class="progress-indicators">
            <div 
              v-for="s in steps" 
              :key="s.id"
              class="progress-dot"
              :class="{ 
                active: currentStep === s.id, 
                completed: currentStep > s.id 
              }"
            >
              <span v-if="currentStep > s.id">✓</span>
              <span v-else>{{ s.id }}</span>
            </div>
          </div>
        </div>

        <!-- Header Section -->
        <div class="modal-header-modern">
          <div class="header-content">
            <h2>Edit Property Details</h2>
            <p>{{ steps[currentStep - 1].label }} - {{ steps[currentStep - 1].description }}</p>
          </div>
        </div>

        <!-- Main Content: Two Column Layout -->
        <div class="modal-body-modern">
          <!-- Left Sidebar: Step Navigation -->
          <div class="step-sidebar">
            <div
              v-for="s in steps"
              :key="s.id"
              class="step-card"
              :class="{ 
                active: currentStep === s.id, 
                completed: currentStep > s.id 
              }"
              @click="setStep(s.id)"
            >
              <div class="step-icon">
                <svg v-if="s.id === 1" class="icon" viewBox="0 0 24 24">
                  <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                <svg v-else-if="s.id === 2" class="icon" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1.41 16.09V20h-2.67v-1.93c-1.71-.36-3.16-1.46-3.27-3.4h1.96c.1 1.05.82 1.87 2.65 1.87 1.96 0 2.4-.98 2.4-1.59 0-.83-.44-1.61-2.67-2.14-2.48-.6-4.18-1.62-4.18-3.67 0-1.72 1.39-2.84 3.11-3.21V4h2.67v1.95c1.86.45 2.79 1.86 2.85 3.39H14.3c-.05-1.11-.64-1.87-2.22-1.87-1.5 0-2.4.68-2.4 1.64 0 .84.65 1.39 2.67 1.91s4.18 1.39 4.18 3.91c-.01 1.83-1.38 2.83-3.12 3.16z"/>
                </svg>
                <svg v-else-if="s.id === 3" class="icon" viewBox="0 0 24 24">
                  <path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V7H1v13h2v-2h18v2h2V10c0-2.21-1.79-4-4-4z"/>
                </svg>
                <svg v-else-if="s.id === 4" class="icon" viewBox="0 0 24 24">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <svg v-else-if="s.id === 5" class="icon" viewBox="0 0 24 24">
                  <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                </svg>
              </div>
              <div class="step-text">
                <span class="step-title">{{ s.label }}</span>
                <span class="step-desc">{{ s.description }}</span>
              </div>
              <div class="step-status">
                <svg v-if="currentStep > s.id" class="icon check" viewBox="0 0 24 24">
                  <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
                <span v-else-if="currentStep === s.id" class="arrow">→</span>
              </div>
            </div>
          </div>

          <!-- Right Content Area: Form Steps -->
          <div class="step-content-modern" ref="stepContent">
            <!-- Basic Info -->
            <div v-show="currentStep === 1" class="step-panel-modern">
              <div class="panel-header">
                <h3>Basic Information</h3>
                <p>Configure your property's core details</p>
              </div>
              <div class="form-grid-modern">
                <div class="form-field">
                  <label>Property Title</label>
                  <input type="text" v-model="editForm.title" class="input-modern" placeholder="e.g. Cozy 2BR Apartment" />
                </div>
                <div class="form-field">
                  <label>WhatsApp Contact</label>
                  <input v-model="editForm.contact" class="input-modern" placeholder="https://wa.me/60123456789" />
                </div>
                <div class="form-field">
                  <label>Available Rooms</label>
                  <input v-model.number="editForm.available_rooms" type="number" class="input-modern" placeholder="0" />
                </div>
                <div class="form-field">
                  <label>Tenants Needed</label>
                  <input v-model.number="editForm.tenants_needed" type="number" class="input-modern" placeholder="0" />
                </div>
                <div class="form-field">
                  <label>Current Tenants</label>
                  <input v-model.number="editForm.current_tenants" type="number" class="input-modern" placeholder="0" />
                </div>
                <div class="form-field">
                  <label>Contract Duration</label>
                  <select v-model="editForm.contract_duration" class="input-modern">
                    <option>6 Months</option>
                    <option>12 Months</option>
                    <option>Flexible</option>
                  </select>
                </div>
              </div>
            </div>

            <!-- Payment -->
            <div v-show="currentStep === 2" class="step-panel-modern">
              <div class="panel-header">
                <h3>Pricing Details</h3>
                <p>Set rent and deposit amounts</p>
              </div>
              <div class="form-grid-modern">
                <div class="form-field highlight">
                  <label>Monthly Rent (RM)</label>
                  <div class="input-with-icon">
                    <span class="currency-symbol">RM</span>
                    <input v-model.number="editForm.payment.amount" type="number" class="input-modern with-prefix" placeholder="0.00" />
                  </div>
                </div>
                <div class="form-field highlight">
                  <label>Deposit (RM)</label>
                  <div class="input-with-icon">
                    <span class="currency-symbol">RM</span>
                    <input v-model.number="editForm.payment.deposit" type="number" class="input-modern with-prefix" placeholder="0.00" />
                  </div>
                </div>
                <div class="form-field full-width">
                  <label>Utilities Included</label>
                  <select v-model="editForm.payment.utility" class="input-modern">
                    <option>Included</option>
                    <option>Not Included</option>
                  </select>
                </div>
                <div v-if="editForm.payment.utility === 'Not Included'" class="form-field full-width">
                  <label>Estimated Utility Cost (RM)</label>
                  <input v-model.number="editForm.payment.additional_bill" type="number" class="input-modern" placeholder="0.00" />
                </div>
              </div>
            </div>

            <!-- Rooms -->
            <div v-show="currentStep === 3" class="step-panel-modern">
              <div class="panel-header">
                <h3>Rooms & Layout</h3>
                <p>Manage bedroom configurations</p>
                <button class="btn-add-room" @click="addRoom">
                  <svg class="icon" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                  Add Room
                </button>
              </div>

              <div class="rooms-grid-modern">
                <div v-for="(room, i) in editForm.rooms || []" :key="room.id || room._tempId" class="room-card-modern">
                  <div class="room-image-wrapper">
                    <img :src="formatImageUrl((room.images && room.images[0]?.image_path) || '')" alt="Room" />
                    <button class="room-edit-btn" @click="editRoom(room)">
                      <svg class="icon" viewBox="0 0 24 24">
                        <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                      </svg>
                    </button>
                  </div>
                  <div class="room-details">
                    <h5>{{ room.room_type || 'Room ' + (i + 1) }}</h5>
                    <div class="room-stats">
                      <span><strong>Capacity:</strong> {{ room.capacity ?? '—' }}</span>
                      <span><strong>Type:</strong> {{ room.bathroom_type || '—' }}</span>
                    </div>
                    <div class="room-tags">
                      <span v-if="room.bed_type" class="tag">{{ room.bed_type }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Additional Rooms Section -->
              <div class="additional-section">
                <div class="additional-header">
                  <div>
                    <h3 class="room-title">
                      🏠 Additional Rooms (Optional)
                    </h3>
                    <p class="additional-subtitle">Add common areas like kitchen, living room, dining room, etc.</p>
                  </div>
                  <button @click="addExtraRoom" class="btn-add-extra">
                    <svg class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                      <line x1="12" y1="5" x2="12" y2="19"></line>
                      <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Add Additional Room
                  </button>
                </div>

                <!-- Empty State -->
                <div v-if="additionalRooms.length === 0" class="empty-additional">
                  <svg class="empty-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                  </svg>
                  <p>No additional rooms added yet</p>
                  <p class="empty-hint">Click the button above to add common areas</p>
                </div>

                <!-- Additional Rooms List -->
                <div v-else class="additional-rooms-list">
                  <div v-for="(extra, index) in additionalRooms" :key="extra._uid" class="additional-room-card">
                    <div class="additional-room-header">
                      <h4 class="additional-room-number">Additional Room {{ index + 1 }}</h4>
                      <button @click="removeExtraRoom(index)" class="btn-remove-extra" title="Remove this room">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                          <line x1="18" y1="6" x2="6" y2="18"></line>
                          <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                      </button>
                    </div>
                    <div class="form-group">
                      <label class="form-label">Room Name</label>
                      <input v-model="extra.name" class="form-input" placeholder="e.g. Kitchen, Dining Room, Living Room" />
                    </div>
                    <div class="form-group">
                      <label class="form-label">Upload Images</label>
                      <input type="file" multiple accept="image/*" @change="handleExtraRoomImages($event, extra)" class="form-input file-input" />
                      <div v-if="extra.images_preview.length" class="image-preview">
                        <img v-for="(img, i) in extra.images_preview" :key="i" :src="img" class="preview-img" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Amenities -->
            <div v-show="currentStep === 4" class="step-panel-modern">
              <div class="panel-header">
                <h3>Amenities & Features</h3>
                <p>Select available amenities</p>
              </div>
              <div class="amenities-grid-modern">
                <label v-for="amen in amenitiesList" :key="amen" class="amenity-checkbox">
                  <input type="checkbox" v-model="editForm.amenities" :value="amen" />
                  <span class="checkbox-box">
                    <svg class="icon" viewBox="0 0 24 24">
                      <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                    </svg>
                  </span>
                  <span class="checkbox-label">{{ amen }}</span>
                </label>
              </div>
            </div>

            <!-- Location -->
            <div v-show="currentStep === 5" class="step-panel-modern">
              <div class="panel-header">
                <h3>Location Details</h3>
                <p>Add proximity and neighborhood info</p>
              </div>
              <div class="form-grid-modern">
                <div class="form-field">
                  <label>Distance from UiTM</label>
                  <input v-model="editForm.distance_ui_tm" class="input-modern" placeholder="e.g. 5 minutes walk" />
                </div>
                <div class="form-field">
                  <label>Nearby Shops</label>
                  <input v-model="editForm.nearby_shop" class="input-modern" placeholder="e.g. 7-Eleven, Mydin" />
                </div>
                <div class="form-field full-width">
                  <label>Nearby Facilities</label>
                  <input v-model="editForm.nearby_facility" class="input-modern" placeholder="e.g. Hospital, Mosque, Park" />
                </div>
                <div class="form-field full-width">
                  <label>Location Advantages</label>
                  <textarea v-model="editForm.location_advantages" rows="4" class="input-modern" placeholder="Describe what makes this location great..."></textarea>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="modal-actions">
              <button class="btn-ghost" @click="closeModal">
                Cancel
              </button>
              <div class="action-group">
                <button v-if="currentStep > 1" class="btn-secondary-modern" @click="prevStep">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                  </svg>
                  Previous
                </button>
                <button v-if="currentStep < steps.length" class="btn-primary-modern" @click="nextStep">
                  Next Step
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
                  </svg>
                </button>
                <button v-else class="btn-primary-modern" @click="updateAll">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                  </svg>
                  Save All Changes
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Save Loading Overlay -->
<div v-if="isSavingProperty" class="save-loading-overlay">
  <div class="save-loading-content">
    <div class="loading-spinner-wrapper">
      <div class="loading-spinner-ring"></div>
      <div class="loading-spinner-ring"></div>
      <div class="loading-spinner-ring"></div>
    </div>
    <p class="loading-text">Saving your property...</p>
    <p class="loading-subtext">Please wait while we update your changes</p>
  </div>
</div>

      </div>
    </div>

    <!-- Room Edit Modal - UPDATED WITH SEPARATE IMAGE SECTIONS -->
    <div v-if="roomEditModal.visible" class="modal-overlay-room-edit" @click="closeRoomEditModal">
      <div class="modal-room-edit" @click.stop>
        <!-- Close Button -->
        <button class="modal-close-room" @click="closeRoomEditModal">
          <svg class="icon" viewBox="0 0 24 24">
            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
          </svg>
        </button>

        <!-- Loading State -->
        <div v-if="roomEditModal.loading" class="room-edit-loading">
          <div class="loading-spinner"></div>
          <p>Loading room details...</p>
        </div>

        <!-- Edit Form -->
        <div v-else class="room-edit-content">
          <div class="room-edit-header">
            <h3>Edit Room Details</h3>
            <p>Update room information and specifications</p>
          </div>

          <div class="room-edit-body">
            <div class="form-grid-modern">
              <div class="form-field">
                <label>Room Type</label>
                <input 
                  type="text" 
                  v-model="roomEditModal.data.room_type" 
                  class="input-modern" 
                  placeholder="e.g. Master Bedroom, Single Room"
                />
              </div>

              <div class="form-field">
                <label>Capacity</label>
                <input 
                  type="number" 
                  v-model.number="roomEditModal.data.capacity" 
                  class="input-modern" 
                  placeholder="Number of people"
                  min="1"
                />
              </div>

              <div class="form-field">
                <label>Bed Type</label>
                <select v-model="roomEditModal.data.bed_type" class="input-modern">
                  <option value="">Select bed type</option>
                  <option value="Single Bed">Single Bed</option>
                  <option value="Double Bed">Double Bed</option>
                  <option value="Queen Bed">Queen Bed</option>
                  <option value="King Bed">King Bed</option>
                  <option value="Bunk Bed">Bunk Bed</option>
                </select>
              </div>

              <div class="form-field">
                <label>Bathroom Type</label>
                <select v-model="roomEditModal.data.bathroom_type" class="input-modern">
                  <option value="">Select bathroom type</option>
                  <option value="Private Bathroom">Private Bathroom</option>
                  <option value="Shared Bathroom">Shared Bathroom</option>
                  <option value="En-suite">En-suite</option>
                </select>
              </div>

              <div class="form-field">
                <label>Current Occupancy</label>
                <input 
                  type="number" 
                  v-model.number="roomEditModal.data.current_occupancy" 
                  class="input-modern" 
                  placeholder="Current tenants"
                  min="0"
                  :max="roomEditModal.data.capacity"
                />
              </div>
            </div>

            <!-- ROOM IMAGES SECTION -->
            <div class="room-image-management">
              <div class="section-header">
                <label>🏠 Room Images</label>
                <button class="btn-add-image" @click="triggerRoomImageUpload">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                  Add Room Images
                </button>
              </div>

              <!-- Current Room Images -->
              <div v-if="roomEditModal.data.room_images && roomEditModal.data.room_images.length > 0" class="current-images-grid">
                <div 
                  v-for="(img, idx) in roomEditModal.data.room_images" 
                  :key="idx"
                  class="image-card"
                >
                  <img :src="formatImageUrl(img.image_path)" alt="Room image" />
                  <div class="image-overlay">
                    <button class="btn-remove-image" @click="removeRoomImage(idx)" title="Remove image">
                      <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- New Room Images Preview -->
              <div v-if="roomEditModal.newRoomImages && roomEditModal.newRoomImages.length > 0" class="new-images-section">
                <label class="new-images-label">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                  New Room Images ({{ roomEditModal.newRoomImages.length }})
                </label>
                <div class="new-images-grid">
                  <div 
                    v-for="(img, idx) in roomEditModal.newRoomImages" 
                    :key="idx"
                    class="image-card new"
                  >
                    <img :src="img.preview" alt="New room image" />
                    <div class="image-overlay">
                      <button class="btn-remove-image" @click="removeNewRoomImage(idx)" title="Remove">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                    <div class="new-badge">NEW</div>
                  </div>
                </div>
              </div>

              <!-- Empty State for Room Images -->
              <div v-if="(!roomEditModal.data.room_images || roomEditModal.data.room_images.length === 0) && (!roomEditModal.newRoomImages || roomEditModal.newRoomImages.length === 0)" class="empty-images-state">
                <svg class="empty-icon" viewBox="0 0 24 24">
                  <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <p>No room images yet</p>
              </div>
            </div>

            <!-- BATHROOM IMAGES SECTION -->
            <div class="room-image-management">
              <div class="section-header">
                <label>🚿 Bathroom Images</label>
                <button class="btn-add-image" @click="triggerBathroomImageUpload">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                  </svg>
                  Add Bathroom Images
                </button>
              </div>

              <!-- Current Bathroom Images -->
              <div v-if="roomEditModal.data.bathroom_images && roomEditModal.data.bathroom_images.length > 0" class="current-images-grid">
                <div 
                  v-for="(img, idx) in roomEditModal.data.bathroom_images" 
                  :key="idx"
                  class="image-card"
                >
                  <img :src="formatImageUrl(img.image_path)" alt="Bathroom image" />
                  <div class="image-overlay">
                    <button class="btn-remove-image" @click="removeBathroomImage(idx)" title="Remove image">
                      <svg class="icon" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                      </svg>
                    </button>
                  </div>
                </div>
              </div>

              <!-- New Bathroom Images Preview -->
              <div v-if="roomEditModal.newBathroomImages && roomEditModal.newBathroomImages.length > 0" class="new-images-section">
                <label class="new-images-label">
                  <svg class="icon" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                  </svg>
                  New Bathroom Images ({{ roomEditModal.newBathroomImages.length }})
                </label>
                <div class="new-images-grid">
                  <div 
                    v-for="(img, idx) in roomEditModal.newBathroomImages" 
                    :key="idx"
                    class="image-card new"
                  >
                    <img :src="img.preview" alt="New bathroom image" />
                    <div class="image-overlay">
                      <button class="btn-remove-image" @click="removeNewBathroomImage(idx)" title="Remove">
                        <svg class="icon" viewBox="0 0 24 24">
                          <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                        </svg>
                      </button>
                    </div>
                    <div class="new-badge">NEW</div>
                  </div>
                </div>
              </div>

              <!-- Empty State for Bathroom Images -->
              <div v-if="(!roomEditModal.data.bathroom_images || roomEditModal.data.bathroom_images.length === 0) && (!roomEditModal.newBathroomImages || roomEditModal.newBathroomImages.length === 0)" class="empty-images-state">
                <svg class="empty-icon" viewBox="0 0 24 24">
                  <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                </svg>
                <p>No bathroom images yet</p>
              </div>
            </div>
          </div>

          <!-- Sticky Footer -->
          <div class="room-edit-footer-sticky">
            <button class="btn-ghost" @click="closeRoomEditModal">
              Cancel
            </button>
            <button class="btn-primary-modern" @click="saveRoomEdit" :disabled="roomEditModal.saving">
              <svg v-if="!roomEditModal.saving" class="icon" viewBox="0 0 24 24">
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
              </svg>
              <div v-else class="button-spinner"></div>
              {{ roomEditModal.saving ? 'Saving...' : 'Save Changes' }}
            </button>
          </div>
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
import { ref, onMounted, watch, computed, nextTick } from 'vue'
import axios from 'axios'
import HeaderNav from '../components/HeaderNav.vue'


// Core reactive state
const user = ref(JSON.parse(localStorage.getItem('user')) || { name: 'Guest' })
const properties = ref([])
const loading = ref(false)
const selectedProperty = ref(null)
const editForm = ref({})
const imageIndexes = ref({})
const searchQuery = ref('')
const showAddPropertyModal = ref(false)
const additionalRooms = ref([])
const isSavingProperty = ref(false) // ✅ NEW: Loading state for saving


// Room Edit Modal State - UPDATED WITH SEPARATE IMAGE TYPES
const roomEditModal = ref({
  visible: false,
  loading: false,
  saving: false,
  data: {
    id: null,
    room_type: '',
    capacity: null,
    current_occupancy: 0,
    bathroom_type: '',
    bed_type: '',
    property_id: null,
    room_images: [],      // NEW: Separate room images
    bathroom_images: []   // NEW: Separate bathroom images
  },
  newRoomImages: [],        // NEW: For new room images to upload
  newBathroomImages: [],    // NEW: For new bathroom images to upload
  roomImagesToDelete: [],   // NEW: Track room images to delete
  bathroomImagesToDelete: [] // NEW: Track bathroom images to delete
})

// Toast Notification State
const toast = ref({
  visible: false,
  message: '',
  type: 'success'
})

// Amenity list
const amenitiesList = [
  'WiFi', 'Parking', 'Refrigerator', 'Washing Machine',
  'Air Conditioning', 'Water Heater', 'Kitchen Access', 'Study Table', 'Wardrobe'
]

// Sidebar Step Navigation
const currentStep = ref(1)
const steps = ref([
  { id: 1, label: 'Basic Info', description: 'Property details' },
  { id: 2, label: 'Payment', description: 'Rent & deposit' },
  { id: 3, label: 'Rooms', description: 'Room configuration' },
  { id: 4, label: 'Amenities', description: 'Features & utilities' },
  { id: 5, label: 'Location', description: 'Nearby & advantages' }
])

// Computed property for filtered properties
const filteredProperties = computed(() => {
  if (!searchQuery.value) return properties.value
  const query = searchQuery.value.toLowerCase()
  return properties.value.filter(p => 
    p.title?.toLowerCase().includes(query) ||
    p.address?.toLowerCase().includes(query)
  )
})

// Watch for modal visibility to control body scroll
watch(
  () => selectedProperty.value,
  (newVal) => {
    if (newVal) {
      document.body.style.overflow = 'hidden'
    } else {
      document.body.style.overflow = 'auto'
    }
  }
)

// Watch for room edit modal visibility
watch(
  () => roomEditModal.value.visible,
  (newVal) => {
    if (newVal) {
      document.body.style.overflow = 'hidden'
    } else if (!selectedProperty.value) {
      document.body.style.overflow = 'auto'
    }
  }
)

// Helper function to format numbers with commas
function formatNumber(num) {
  return num?.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") || '0'
}

function setStep(num) {
  currentStep.value = num
}

function nextStep() {
  if (currentStep.value < steps.value.length) currentStep.value++
}

function prevStep() {
  if (currentStep.value > 1) currentStep.value--
}

// Toast notification function
function showToast(message, type = 'success') {
  toast.value.message = message
  toast.value.type = type
  toast.value.visible = true
  
  setTimeout(() => {
    toast.value.visible = false
  }, 3000)
}

// Debounce watcher to detect room count changes
let watcherGuard = false
watch(
  () => editForm.value?.available_rooms,
  (newVal, oldVal) => {
    if (!selectedProperty.value) return
    if (watcherGuard) return
    const num = Number(newVal)
    const prev = Number(oldVal)
    if (isNaN(num) || num < 0) return

    if (num > prev) {
      watcherGuard = true
      showToast(`⚠️ You increased available rooms from ${prev} → ${num}. Please manually add ${num - prev} new room(s).`, 'error')
      setTimeout(() => (watcherGuard = false), 800)
    } else if (num < prev) {
      watcherGuard = true
      showToast(`⚠️ You reduced available rooms from ${prev} → ${num}. Please manually delete ${prev - num} room(s).`, 'error')
      setTimeout(() => (watcherGuard = false), 800)
    }
  }
)

// Fetch properties + payment info
async function fetchProperties() {
  try {
    loading.value = true
    const res = await axios.get('/api/properties')
    const userId = user.value?.id
    const data = Array.isArray(res.data) ? res.data : (res.data?.data || [])

    const withPayments = await Promise.all(
      data
        .filter(p => p.user_id === userId)
        .map(async p => {
          const payRes = await axios.get(`/api/payments?property_id=${p.id}`)
          let payment = Array.isArray(payRes.data)
            ? payRes.data.find(x => x.property_id === p.id) || payRes.data[0]
            : payRes.data

          let imgs = p.images
          if (typeof imgs === 'string') {
            try { imgs = JSON.parse(imgs) } catch {}
          }
          let imagesArray = Array.isArray(imgs)
            ? imgs.map(i =>
                i.startsWith('http')
                  ? i
                  : `https://tapahhomebased-production.up.railway.app${String(i).replace(/\\/g, '')}`
              )
            : []

          try {
            const roomsRes = await axios.get(`/api/properties/${p.id}/rooms`)
            const rooms = Array.isArray(roomsRes.data) ? roomsRes.data : []
            
            rooms.forEach(room => {
              if (room.images && Array.isArray(room.images)) {
                room.images.forEach(img => {
                  if (img.image_path) {
                    const url = img.image_path.startsWith('http')
                      ? img.image_path
                      : `https://tapahhomebased-production.up.railway.app${String(img.image_path).replace(/\\/g, '')}`
                    imagesArray.push(url)
                  }
                })
              }
            })

            const addRoomsRes = await axios.get(`/api/properties/${p.id}/additional-rooms`)
            const additionalRooms = Array.isArray(addRoomsRes.data) ? addRoomsRes.data : []
            
            additionalRooms.forEach(room => {
              if (room.image_path) {
                const url = room.image_path.startsWith('http')
                  ? room.image_path
                  : `https://tapahhomebased-production.up.railway.app${String(room.image_path).replace(/\\/g, '')}`
                imagesArray.push(url)
              }
            })
          } catch (err) {
            console.log('Could not fetch room images:', err)
          }

          if (imagesArray.length === 0) {
            imagesArray = ['/default-house.jpg']
          }

          return { ...p, imagesArray, payment }
        })
    )

    properties.value = withPayments
    properties.value.forEach(p => (imageIndexes.value[p.id] = 0))
  } catch (err) {
    console.error('Error fetching properties:', err)
    showToast('Failed to load properties', 'error')
  } finally {
    loading.value = false
  }
}

// Carousel helpers
const nextImage = id => {
  const prop = properties.value.find(p => p.id === id)
  if (!prop) return
  imageIndexes.value[id] = (imageIndexes.value[id] + 1) % (prop.imagesArray?.length || 1)
}

const prevImage = id => {
  const prop = properties.value.find(p => p.id === id)
  if (!prop) return
  const len = prop.imagesArray?.length || 1
  imageIndexes.value[id] = (imageIndexes.value[id] - 1 + len) % len
}

const getImage = (property, index) =>
  property.imagesArray?.[index] || '/default-house.jpg'

// Helper – Detect empty room
function isRoomGhost(room) {
  if (!room) return true
  const textVals = [room.room_type, room.bathroom_type, room.bed_type]
  const nonEmptyText = textVals.some(v => typeof v === 'string' && v.trim().length > 0)
  const hasCapacity = !!(room.capacity && Number(room.capacity) > 0)
  return !nonEmptyText && !hasCapacity
}

// Open modal and load property
async function openProperty(property) {
  try {
    console.log('🔍 Opening property:', property.id)
    
    // Load all data in parallel, INCLUDING dedicated additional rooms endpoint
    const [propRes, payRes, roomsRes, additionalRes] = await Promise.all([
      axios.get(`/api/properties/${property.id}`),
      axios.get(`/api/payments?property_id=${property.id}`),
      axios.get(`/api/properties/${property.id}/rooms`),
      axios.get(`/api/properties/${property.id}/additional-rooms`) // ✅ DIRECT ENDPOINT
    ])

    console.log('📦 Property response:', propRes.data)
    console.log('🏠 Additional rooms from dedicated endpoint:', additionalRes.data)

    // Handle payment
    let payment = null
    if (Array.isArray(payRes.data) && payRes.data.length > 0)
      payment = payRes.data.find(x => x.property_id === property.id) || payRes.data[0]
    else payment = payRes.data || { amount: 0, deposit: 0, utility: 'Disertakan' }

    // Handle rooms
    const roomsFromServer = Array.isArray(roomsRes.data) ? roomsRes.data : []
    const visibleRooms = roomsFromServer
      .sort((a, b) => (a.id || 0) - (b.id || 0))
      .map(r => ({ ...r, _delete: false }))

    // ✅ HANDLE ADDITIONAL ROOMS FROM DEDICATED ENDPOINT
    const rawAdditionalData = additionalRes.data
    console.log('🔍 Type of additionalRes.data:', typeof rawAdditionalData)
    console.log('🔍 Is array?', Array.isArray(rawAdditionalData))
    
    // Handle different response formats
    let additionalRoomsArray = []
    if (Array.isArray(rawAdditionalData)) {
      additionalRoomsArray = rawAdditionalData
    } else if (rawAdditionalData && Array.isArray(rawAdditionalData.data)) {
      additionalRoomsArray = rawAdditionalData.data
    } else if (rawAdditionalData && typeof rawAdditionalData === 'object') {
      // If it's a single object, wrap it in array
      additionalRoomsArray = [rawAdditionalData]
    }

    console.log('🔍 additionalRoomsArray:', additionalRoomsArray)
    console.log('🔍 Count:', additionalRoomsArray.length)

    // Map to frontend format
    const loadedAdditionalRooms = additionalRoomsArray.map((r, idx) => {
      console.log(`🔍 Mapping room ${idx}:`, r)
      
      // Build image URL
      let imageUrl = '/default-room.jpg'
      if (r.image_path) {
        imageUrl = r.image_path.startsWith('http')
          ? r.image_path
          : `https://tapahhomebased-production.up.railway.app${r.image_path}`
      }
      
      return {
        _uid: r.id || Date.now() + idx,
        id: r.id,
        name: r.name || '',
        images: [], // For new uploads
        images_preview: [imageUrl],
        image_path: r.image_path,
        property_id: r.property_id || property.id,
        original_name: r.name // Track original for edit detection
      }
    })

    console.log('✅ FINAL loadedAdditionalRooms:', loadedAdditionalRooms)

    // Set property
    selectedProperty.value = propRes.data
    
    // Build edit form
    editForm.value = {
      ...propRes.data,
      payment,
      rooms: visibleRooms,
      additional_rooms: loadedAdditionalRooms
    }

    // ✅ CRITICAL: Set the reactive ref that the template uses
    additionalRooms.value = loadedAdditionalRooms
    
    console.log('✅ additionalRooms.value is now:', additionalRooms.value)
    console.log('✅ Length:', additionalRooms.value.length)

    // Go to first step
    currentStep.value = 1
    
    // Force Vue to recognize the change
    nextTick(() => {
      console.log('🔄 After nextTick, additionalRooms.value:', additionalRooms.value)
    })
    
  } catch (err) {
    console.error('❌ Failed to load property details:', err)
    console.error('❌ Error details:', err.response?.data || err.message)
    showToast('Failed to load property details', 'error')
  }
}

// Edit Room - UPDATED TO SEPARATE IMAGE TYPES
function editRoom(room) {
  if (!room.id) {
    showToast('Cannot edit unsaved room', 'error')
    return
  }

  roomEditModal.value.visible = true
  roomEditModal.value.loading = true

  // Separate images by type
  const roomImages = []
  const bathroomImages = []
  
  if (room.images && Array.isArray(room.images)) {
    room.images.forEach(img => {
      if (img.type === 'bathroom') {
        bathroomImages.push(img)
      } else {
        roomImages.push(img)
      }
    })
  }

  setTimeout(() => {
    roomEditModal.value.data = {
      id: room.id,
      room_type: room.room_type || '',
      capacity: room.capacity || null,
      current_occupancy: room.current_occupancy || 0,
      bathroom_type: room.bathroom_type || '',
      bed_type: room.bed_type || '',
      property_id: room.property_id || editForm.value.id,
      room_images: roomImages,
      bathroom_images: bathroomImages
    }
    roomEditModal.value.newRoomImages = []
    roomEditModal.value.newBathroomImages = []
    roomEditModal.value.roomImagesToDelete = []
    roomEditModal.value.bathroomImagesToDelete = []
    roomEditModal.value.loading = false
  }, 300)
}

// NEW: Trigger room image upload
function triggerRoomImageUpload() {
  const input = document.createElement('input')
  input.type = 'file'
  input.accept = 'image/*'
  input.multiple = true
  
  input.onchange = (e) => {
    handleRoomImageUpload(e.target.files)
  }
  
  input.click()
}

// NEW: Trigger bathroom image upload
function triggerBathroomImageUpload() {
  const input = document.createElement('input')
  input.type = 'file'
  input.accept = 'image/*'
  input.multiple = true
  
  input.onchange = (e) => {
    handleBathroomImageUpload(e.target.files)
  }
  
  input.click()
}

// NEW: Handle room image upload
function handleRoomImageUpload(files) {
  if (!files || files.length === 0) return

  Array.from(files).forEach(file => {
    if (!file.type.startsWith('image/')) {
      showToast('Please select only image files', 'error')
      return
    }

    if (file.size > 5 * 1024 * 1024) {
      showToast('Image size should be less than 5MB', 'error')
      return
    }

    const reader = new FileReader()
    reader.onload = (e) => {
      roomEditModal.value.newRoomImages.push({
        file: file,
        preview: e.target.result,
        name: file.name,
        type: 'room'
      })
    }
    reader.readAsDataURL(file)
  })
}

// NEW: Handle bathroom image upload
function handleBathroomImageUpload(files) {
  if (!files || files.length === 0) return

  Array.from(files).forEach(file => {
    if (!file.type.startsWith('image/')) {
      showToast('Please select only image files', 'error')
      return
    }

    if (file.size > 5 * 1024 * 1024) {
      showToast('Image size should be less than 5MB', 'error')
      return
    }

    const reader = new FileReader()
    reader.onload = (e) => {
      roomEditModal.value.newBathroomImages.push({
        file: file,
        preview: e.target.result,
        name: file.name,
        type: 'bathroom'
      })
    }
    reader.readAsDataURL(file)
  })
}

// NEW: Remove room image
function removeRoomImage(index) {
  if (!confirm('Are you sure you want to remove this room image?')) return
  
  const image = roomEditModal.value.data.room_images[index]
  if (image && image.id) {
    roomEditModal.value.roomImagesToDelete.push(image.id)
  }
  
  roomEditModal.value.data.room_images.splice(index, 1)
  showToast('Room image marked for removal', 'success')
}

// NEW: Remove bathroom image
function removeBathroomImage(index) {
  if (!confirm('Are you sure you want to remove this bathroom image?')) return
  
  const image = roomEditModal.value.data.bathroom_images[index]
  if (image && image.id) {
    roomEditModal.value.bathroomImagesToDelete.push(image.id)
  }
  
  roomEditModal.value.data.bathroom_images.splice(index, 1)
  showToast('Bathroom image marked for removal', 'success')
}

// NEW: Remove new room image
function removeNewRoomImage(index) {
  roomEditModal.value.newRoomImages.splice(index, 1)
}

// NEW: Remove new bathroom image
function removeNewBathroomImage(index) {
  roomEditModal.value.newBathroomImages.splice(index, 1)
}

// Save Room Edit - UPDATED WITH SEPARATE IMAGE HANDLING
async function saveRoomEdit() {
  if (roomEditModal.value.saving) return

  // Validation
  if (!roomEditModal.value.data.room_type?.trim()) {
    showToast('Room type is required', 'error')
    return
  }

  if (!roomEditModal.value.data.capacity || roomEditModal.value.data.capacity < 1) {
    showToast('Capacity must be at least 1', 'error')
    return
  }

  roomEditModal.value.saving = true

  try {
    const propertyId = editForm.value.id
    const roomId = roomEditModal.value.data.id

    // 1. Update basic room details
    const payload = {
      room_type: roomEditModal.value.data.room_type.trim(),
      capacity: Number(roomEditModal.value.data.capacity),
      current_occupancy: Number(roomEditModal.value.data.current_occupancy) || 0,
      bathroom_type: roomEditModal.value.data.bathroom_type?.trim() || null,
      bed_type: roomEditModal.value.data.bed_type?.trim() || null,
      property_id: propertyId
    }

    await axios.put(`/api/properties/${propertyId}/rooms/${roomId}`, payload)

    // 2. Delete marked room images
    for (const imageId of roomEditModal.value.roomImagesToDelete) {
      try {
        await axios.delete(`/api/room-images/${imageId}`)
      } catch (err) {
        console.error('Failed to delete room image:', imageId, err)
      }
    }

    // 3. Delete marked bathroom images
    for (const imageId of roomEditModal.value.bathroomImagesToDelete) {
      try {
        await axios.delete(`/api/room-images/${imageId}`)
      } catch (err) {
        console.error('Failed to delete bathroom image:', imageId, err)
      }
    }

    // 4. Upload new room images
    for (const newImage of roomEditModal.value.newRoomImages) {
      try {
        const formData = new FormData()
        formData.append('room_images[]', newImage.file)

        await axios.post(`/api/rooms/${roomId}/images`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      } catch (err) {
        console.error('Failed to upload room image:', newImage.name, err)
        showToast(`Failed to upload ${newImage.name}`, 'error')
      }
    }

    // 5. Upload new bathroom images
    for (const newImage of roomEditModal.value.newBathroomImages) {
      try {
        const formData = new FormData()
        formData.append('bathroom_images[]', newImage.file)

        await axios.post(`/api/rooms/${roomId}/images`, formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        })
      } catch (err) {
        console.error('Failed to upload bathroom image:', newImage.name, err)
        showToast(`Failed to upload ${newImage.name}`, 'error')
      }
    }

    showToast('Room updated successfully!', 'success')

    // Refresh room data
    const roomsRes = await axios.get(`/api/properties/${propertyId}/rooms`)
    const updatedRooms = Array.isArray(roomsRes.data) ? roomsRes.data : []
    editForm.value.rooms = updatedRooms.map(r => ({ ...r, _delete: false }))

    closeRoomEditModal()
  } catch (err) {
    console.error('Failed to update room:', err)
    showToast('Failed to update room', 'error')
  } finally {
    roomEditModal.value.saving = false
  }
}

// Close Room Edit Modal
function closeRoomEditModal() {
  roomEditModal.value.visible = false
  roomEditModal.value.data = {
    id: null,
    room_type: '',
    capacity: null,
    current_occupancy: 0,
    bathroom_type: '',
    bed_type: '',
    property_id: null,
    room_images: [],
    bathroom_images: []
  }
  roomEditModal.value.newRoomImages = []
  roomEditModal.value.newBathroomImages = []
  roomEditModal.value.roomImagesToDelete = []
  roomEditModal.value.bathroomImagesToDelete = []
}

// Add / Remove Room
function addRoom() {
  if (!editForm.value.rooms) editForm.value.rooms = []
  editForm.value.rooms.push({
    _tempId: Date.now(),
    room_type: '',
    capacity: null,
    current_occupancy: 0,
    bathroom_type: '',
    bed_type: '',
    property_id: editForm.value?.id || null,
    _delete: false
  })
}

function addExtraRoom() {
  additionalRooms.value.push({
    _uid: Date.now(),
    name: '',
    images: [],
    images_preview: []
  })
}

function removeExtraRoom(index) {
  additionalRooms.value.splice(index, 1)
}

function handleExtraRoomImages(event, extra) {
  const files = event.target.files
  if (!files || files.length === 0) return

  extra.images = Array.from(files)
  extra.images_preview = []

  Array.from(files).forEach(file => {
    const reader = new FileReader()
    reader.onload = (e) => {
      extra.images_preview.push(e.target.result)
    }
    reader.readAsDataURL(file)
  })
}

function removeRoom(id, index) {
  if (!confirm('Delete this room?')) return
  const room = editForm.value.rooms[index]
  if (!room) return
  if (room.id) room._delete = true
  else editForm.value.rooms.splice(index, 1)
  showToast('🗑 Room marked for deletion. Click Save to confirm.', 'error')
}

// Image Handling
function formatImageUrl(path) {
  if (!path) return '/default-room.jpg'
  return path.startsWith('http')
    ? path
    : `https://tapahhomebased-production.up.railway.app${String(path).replace(/\\/g, '')}`
}

// ✅ UPDATED: Save All Changes with Loading Animation
async function updateAll() {
  // Start loading animation
  isSavingProperty.value = true
  
  try {
    const propertyId = editForm.value.id
    if (!propertyId) {
      showToast('Missing property ID', 'error')
      return
    }

    // Update property basic info
    await axios.put(`/api/properties/${propertyId}`, {
      title: editForm.value.title,
      contact: editForm.value.contact,
      available_rooms: editForm.value.available_rooms,
      tenants_needed: editForm.value.tenants_needed,
      current_tenants: editForm.value.current_tenants,
      contract_duration: editForm.value.contract_duration,
      amenities: editForm.value.amenities,
      distance_ui_tm: editForm.value.distance_ui_tm,
      nearby_shop: editForm.value.nearby_shop,
      nearby_facility: editForm.value.nearby_facility,
      location_advantages: editForm.value.location_advantages
    })

    // Update payment info
    const pay = editForm.value.payment
    if (pay?.id) {
      await axios.put(`/api/payments/${pay.id}`, pay)
    } else if (pay && (pay.amount || pay.deposit)) {
      await axios.post(`/api/payments`, { ...pay, property_id: propertyId })
    }

    // Update rooms
    for (const room of editForm.value.rooms || []) {
      if (room._delete && room.id) {
        await axios.delete(`/api/properties/${propertyId}/rooms/${room.id}`)
        continue
      }
      if (room._delete || isRoomGhost(room)) continue
      
      const payload = {
        room_type: room.room_type?.trim() || null,
        capacity: room.capacity ? Number(room.capacity) : null,
        current_occupancy: room.current_occupancy ? Number(room.current_occupancy) : 0,
        bathroom_type: room.bathroom_type?.trim() || null,
        bed_type: room.bed_type?.trim() || null,
        property_id: propertyId
      }
      
      if (room.id) {
        await axios.put(`/api/properties/${propertyId}/rooms/${room.id}`, payload)
      } else {
        await axios.post(`/api/properties/${propertyId}/rooms`, payload)
      }
    }

    // Handle Additional Rooms (Create / Update / Delete)
    for (const extra of additionalRooms.value) {
      // Update existing additional room
      if (extra.id) {
        const nameChanged = extra.name && extra.name !== extra.original_name
        const hasNewImage = extra.images && extra.images.length > 0

        if (nameChanged || hasNewImage) {
          const formData = new FormData()
          if (extra.name) formData.append('name', extra.name)

          if (hasNewImage) {
            for (const file of extra.images) {
              formData.append('images[]', file)
            }
          }

          try {
            await axios.post(`/api/additional-rooms/${extra.id}?_method=PUT`, formData, {
              headers: { 'Content-Type': 'multipart/form-data' }
            })
            console.log(`✅ Updated additional room ${extra.id}`)
          } catch (err) {
            console.error(`❌ Failed to update additional room ${extra.id}`, err)
            showToast(`Failed to update ${extra.name}`, 'error')
          }
        }
      } 
      // Create new additional room
      else if (extra.images && extra.images.length > 0 && extra.name.trim()) {
        const formData = new FormData()
        formData.append('name', extra.name)
        for (const file of extra.images) {
          formData.append('images[]', file)
        }

        try {
          await axios.post(`/api/properties/${editForm.value.id}/additional-rooms`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
          })
          console.log(`✅ Created new additional room: ${extra.name}`)
        } catch (err) {
          console.error('❌ Failed to create new additional room', err)
          showToast(`Failed to create ${extra.name}`, 'error')
        }
      }
    }

    // Delete removed additional rooms
    const deletedRooms = selectedProperty.value.additionalRooms?.filter(
      oldRoom => !additionalRooms.value.some(newRoom => newRoom.id === oldRoom.id)
    )

    for (const del of deletedRooms || []) {
      try {
        await axios.delete(`/api/additional-rooms/${del.id}`)
        console.log(`🗑 Deleted additional room ${del.name}`)
      } catch (err) {
        console.error('Failed to delete additional room:', del.name, err)
      }
    }

    showToast('✅ All updates saved successfully!', 'success')
    
    // Wait a bit to show success message
    await new Promise(resolve => setTimeout(resolve, 800))
    
    closeModal()
    await fetchProperties()
    
  } catch (err) {
    console.error('Update failed:', err)
    showToast('❌ Update failed. Check console for details.', 'error')
  } finally {
    // Stop loading animation
    isSavingProperty.value = false
  }
}

// Close Modal
const closeModal = () => {
  selectedProperty.value = null
  editForm.value = {}
  additionalRooms.value = []
}

onMounted(fetchProperties)
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
  --background: #FAFCFC;
  --text-dark: #0e7490;
  --text-medium: #0891b2;
  --text-muted: #64748b;
  --text-light: #94a3b8;
  --border-light: #ccfbf1;
  --white: #FFFFFF;
  --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04);
  --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.06);
  --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
  --shadow-xl: 0 12px 40px rgba(0, 0, 0, 0.1);
  --shadow-teal: 0 4px 20px rgba(20, 184, 166, 0.15);
  --glow-teal: 0 0 20px rgba(20, 184, 166, 0.3);
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
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

.dashboard-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  background: var(--very-light-teal);
}

/* Hero Header - UPDATED WITH TEAL GRADIENT */
.hero-header {
  position: relative;
  padding: 3.5rem 2rem 4.5rem;
  overflow: hidden;
  min-height: 350px;
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
  background-repeat: no-repeat;
}

.hero-gradient-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(
    135deg,
    rgba(20, 184, 166, 0.55) 0%,
    rgba(6, 182, 212, 0.65) 50%,
    rgba(16, 185, 129, 0.55) 100%
  );
  backdrop-filter: blur(12px) saturate(150%);
  -webkit-backdrop-filter: blur(12px) saturate(150%);
  box-shadow: inset 0 0 80px rgba(255, 255, 255, 0.1);
  animation: shinyFlow 12s ease-in-out infinite alternate;
  background-size: 200% 200%;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}


.hero-content {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
  text-align: left;
  width: 100%;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: rgba(255, 255, 255, 0.2);
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
  font-size: 3rem;
  font-weight: 800;
  color: white;
  margin-bottom: 0.75rem;
  letter-spacing: -0.02em;
  line-height: 1.1;
  text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.hero-subtitle {
  font-size: 1.25rem;
  color: rgba(255, 255, 255, 0.95);
  margin-bottom: 2.5rem;
  font-weight: 500;
  text-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
}



/* Stats Bar - UPDATED */
.stats-bar {
  display: flex;
  gap: 2rem;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(20px);
  padding: 1.25rem 1.75rem;
  border-radius: 16px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  max-width: 550px;
}

.stat-item {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: white;
  line-height: 1;
}

.stat-label {
  font-size: 0.875rem;
  color: rgba(255, 255, 255, 0.9);
  font-weight: 500;
}

.stat-divider {
  width: 1px;
  background: rgba(255, 255, 255, 0.3);
}

/* Main Content */
.main-content {
  flex: 1;
  padding: 0 2rem 4rem;
  margin-top: -2.5rem;
  position: relative;
  z-index: 2;
}

.content-container {
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}



/* Search Bar - UPDATED WITH TEAL */
.search-section {
  margin-bottom: 2.5rem;
}

.search-bar-container {
  background: linear-gradient(135deg, #0e7490 0%, #0891b2 100%);
  border-radius: 12px;
  padding: 1.25rem 1.5rem;
  box-shadow: var(--shadow-lg), var(--glow-teal);
  display: flex;
  align-items: center;
  gap: 1rem;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.search-icon {
  width: 24px;
  height: 24px;
  fill: white;
  flex-shrink: 0;
  opacity: 0.9;
}

.search-input {
  flex: 1;
  background: transparent;
  border: none;
  color: white;
  font-size: 1.063rem;
  font-weight: 500;
  padding: 0.5rem;
  outline: none;
  font-family: inherit;
}

.search-input::placeholder {
  color: rgba(255, 255, 255, 0.7);
  font-weight: 400;
}

/* State Containers */
.state-container {
  text-align: center;
  padding: 4rem 2rem;
  background: var(--white);
  border-radius: 20px;
  box-shadow: var(--shadow-md);
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid var(--light-teal);
  border-top-color: var(--primary-teal);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin: 0 auto 1.5rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.state-text {
  color: var(--text-muted);
  font-size: 1.125rem;
}

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
  margin-bottom: 2rem;
  max-width: 400px;
  margin-left: auto;
  margin-right: auto;
}

/* Properties Grid - SMALLER CARDS */
.properties-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
  gap: 2rem;
}

/* Property Card - SMALLER & SIMPLIFIED */
.property-card-wrapper {
  position: relative;
  cursor: pointer;
}

.turquoise-glow-border {
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(8, 145, 178, 0.08));
  border: 2px solid rgba(20, 184, 166, 0.25);
  border-radius: 16px;
  padding: 6px;
  transition: all 0.3s ease;
}

.turquoise-glow-border:hover {
  border-color: rgba(20, 184, 166, 0.5);
  box-shadow: 0 8px 24px rgba(20, 184, 166, 0.2);
  transform: translateY(-3px);
}

.property-card {
  background: var(--white);
  border-radius: 12px;
  overflow: hidden;
  transition: var(--transition);
}

.card-image-section {
  position: relative;
  height: 220px;
  overflow: hidden;
  background: var(--light-teal);
}

.card-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.turquoise-glow-border:hover .card-image {
  transform: scale(1.05);
}

.availability-badge {
  position: absolute;
  top: 0.875rem;
  left: 0.875rem;
  padding: 0.5rem 0.875rem;
  border-radius: 50px;
  font-size: 0.813rem;
  font-weight: 600;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  background: rgba(100, 116, 139, 0.9);
  color: white;
  z-index: 2;
}

.availability-badge.available {
  background: rgba(20, 184, 166, 0.95);
  border-color: rgba(255, 255, 255, 0.3);
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.3);
}

.carousel-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 0.75rem;
  opacity: 0;
  transition: opacity 0.3s;
}

.turquoise-glow-border:hover .carousel-overlay {
  opacity: 1;
}

.carousel-button {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.carousel-button:hover {
  background: white;
  transform: scale(1.1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.carousel-button .icon {
  width: 18px;
  height: 18px;
  fill: var(--text-dark);
}

.image-dots {
  position: absolute;
  bottom: 0.875rem;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  z-index: 2;
}

.dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.5);
  transition: all 0.3s;
  cursor: pointer;
}

.dot.active {
  background: white;
  transform: scale(1.2);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.card-content {
  padding: 1.5rem;
  background: linear-gradient(to bottom, rgba(236, 254, 255, 0.3), rgba(255, 255, 255, 0.95));
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 0.625rem;
}

.property-name {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--text-dark);
  line-height: 1.3;
  flex: 1;
  margin-right: 0.5rem;
}

.quick-edit {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: var(--light-teal);
  border: 1px solid rgba(20, 184, 166, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  flex-shrink: 0;
}

.quick-edit:hover {
  background: var(--primary-teal);
  border-color: var(--primary-teal);
  transform: scale(1.05);
}

.quick-edit .icon {
  width: 14px;
  height: 14px;
  fill: var(--primary-teal);
}

.quick-edit:hover .icon {
  fill: white;
}

.property-location {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: var(--text-muted);
  font-size: 0.875rem;
  margin-bottom: 1rem;
  line-height: 1.4;
}

.location-icon {
  width: 14px;
  height: 14px;
  fill: var(--text-muted);
  flex-shrink: 0;
}

.property-features {
  display: flex;
  gap: 1rem;
  margin-bottom: 1.25rem;
  padding-bottom: 1.25rem;
  border-bottom: 1px solid rgba(20, 184, 166, 0.15);
}

.feature {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  font-size: 0.875rem;
  color: var(--text-medium);
  font-weight: 500;
}

.feature-icon {
  width: 16px;
  height: 16px;
  fill: var(--primary-teal);
  flex-shrink: 0;
}

.pricing-section {
  margin-bottom: 1rem;
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(8, 145, 178, 0.05));
  padding: 0.875rem;
  border-radius: 10px;
  border: 1px solid rgba(20, 184, 166, 0.15);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.price-main {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
  margin-bottom: 0.375rem;
}

.price-amount {
  font-size: 1.75rem;
  font-weight: 800;
  color: #0891b2;
  line-height: 1;
}

.price-period {
  font-size: 0.938rem;
  color: var(--text-muted);
  font-weight: 500;
}

.price-details {
  display: flex;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.deposit-label {
  color: var(--text-muted);
  font-weight: 500;
}

.deposit-amount {
  color: var(--deep-teal);
  font-weight: 700;
}

.card-footer {
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.08), rgba(8, 145, 178, 0.05));
  border-radius: 10px;
  padding: 0.875rem;
}

.btn-view {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #0e7490, #0891b2);
  color: #FFFFFF;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
}

.btn-view:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-2px);
  box-shadow: 0 6px 18px rgba(20, 184, 166, 0.35);
}

.btn-view .icon {
  width: 16px;
  height: 16px;
  fill: white;
  transition: transform 0.3s;
}

.btn-view:hover .icon {
  transform: translateX(3px);
}



/* Floating Action Button */
.fab {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 1rem 1.5rem;
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  color: white;
  border: none;
  border-radius: 50px;
  font-weight: 600;
  font-size: 0.938rem;
  cursor: pointer;
  box-shadow: var(--shadow-xl), var(--glow-teal);
  transition: var(--transition);
  z-index: 100;
}

.fab:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 12px 40px rgba(20, 184, 166, 0.4);
}

.fab .icon {
  width: 20px;
  height: 20px;
  fill: white;
}

.fab-label {
  font-weight: 600;
}

/* Button Styles */
.btn-primary {
  background: linear-gradient(135deg, var(--primary-teal), var(--deep-teal));
  color: white;
  border: none;
  padding: 0.875rem 1.75rem;
  border-radius: 12px;
  font-weight: 600;
  font-size: 0.938rem;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: var(--transition);
  box-shadow: var(--shadow-teal);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(20, 184, 166, 0.3);
}

.btn-primary.btn-large {
  padding: 1rem 2rem;
  font-size: 1rem;
}

.icon {
  width: 20px;
  height: 20px;
  fill: currentColor;
}

/* ============================================
   MODAL STYLES - MATCHING UPDATED PAGE
   ============================================ */

.modal-overlay-modern {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
  padding: 2rem;
  animation: fadeInModal 0.3s ease;
}

@keyframes fadeInModal {
  from { 
    opacity: 0; 
    backdrop-filter: blur(0px);
  }
  to { 
    opacity: 1;
    backdrop-filter: blur(8px);
  }
}

.modal-wizard {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 1.5rem;
  width: 100%;
  max-width: 1100px;
  max-height: 90vh;
  overflow: hidden;
  box-shadow: 
    0 0 60px rgba(6, 182, 212, 0.15),
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.6);
  border: 1px solid rgba(6, 182, 212, 0.2);
  display: flex;
  flex-direction: column;
  position: relative;
  animation: slideUpModal 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-wizard::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #06b6d4, #14b8a6, #2dd4bf, #06b6d4);
  background-size: 200% 100%;
  animation: shimmer 3s linear infinite;
  z-index: 10;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

@keyframes slideUpModal {
  from {
    opacity: 0;
    transform: translateY(60px) scale(0.9);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.modal-close-modern {
  position: absolute;
  top: 1.25rem;
  right: 1.25rem;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: white;
  border: 1px solid #E0E0E0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  z-index: 10;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.modal-close-modern:hover {
  background: #F5F5F5;
  transform: scale(1.1);
}

.modal-close-modern .icon {
  width: 20px;
  height: 20px;
  fill: #666;
  transition: fill 0.3s;
}

/* Progress Bar */
.modal-progress-bar {
  padding: 1.5rem 2.5rem 0.75rem;
  background: linear-gradient(135deg, #0e7490 0%, #0891b2 100%);
  position: relative;
}

.progress-track {
  height: 4px;
  background: rgba(255, 255, 255, 0.3);
  border-radius: 10px;
  overflow: hidden;
  margin-bottom: 1.25rem;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
  position: relative;
  z-index: 1;
}

.progress-fill {
  height: 100%;
  background: linear-gradient(90deg, #2dd4bf, #14b8a6);
  border-radius: 10px;
  transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 0 15px rgba(45, 212, 191, 0.6);
  position: relative;
}

.progress-indicators {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  position: relative;
  z-index: 1;
}

.progress-indicators::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 18px;
  right: 18px;
  height: 2px;
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-50%);
  z-index: 0;
}

.progress-dot {
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.2);
  border: 2px solid rgba(255, 255, 255, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.875rem;
  font-weight: 700;
  color: white;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  z-index: 1;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.progress-dot.active {
  background: linear-gradient(135deg, #2dd4bf, #14b8a6);
  border-color: #2dd4bf;
  color: white;
  box-shadow: 0 4px 12px rgba(45, 212, 191, 0.4);
  transform: scale(1.1);
}

.progress-dot.completed {
  background: rgba(45, 212, 191, 0.9);
  border-color: #2dd4bf;
  color: white;
  box-shadow: 0 0 10px rgba(45, 212, 191, 0.5);
}

/* Modal Header */
.modal-header-modern {
  padding: 1.5rem 2.5rem;
  border-bottom: 1px solid #ccfbf1;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: white;
}

.header-content h2 {
  font-size: 1.875rem;
  font-weight: 700;
  background: linear-gradient(135deg, #0891b2, #14b8a6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.375rem;
  position: relative;
  display: inline-block;
}

.header-content h2::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #14b8a6, #2dd4bf);
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.4);
}

.header-content p {
  color: #64748b;
  font-size: 0.938rem;
  font-weight: 400;
}

/* Modal Body */
.modal-body-modern {
  display: flex;
  flex: 1;
  overflow: hidden;
  max-height: calc(90vh - 240px);
  background: white;
}

/* Left Sidebar */
.step-sidebar {
  width: 280px;
  background: linear-gradient(135deg, #0e7490 0%, #0891b2 100%);
  padding: 1.5rem;
  display: flex;
  flex-direction: column;
  position: relative;
  overflow-y: auto;
  overflow-x: hidden;
  flex-shrink: 0;
}

.step-sidebar::-webkit-scrollbar {
  width: 6px;
}

.step-sidebar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
}

.step-sidebar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 10px;
}

.step-sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}

.step-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.125rem;
  border-radius: 0.75rem;
  margin-bottom: 0.75rem;
  cursor: pointer;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: none;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  color: rgba(255, 255, 255, 0.9);
  position: relative;
  overflow: hidden;
  z-index: 1;
}

.step-card:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateX(4px);
}

.step-card.active {
  background: linear-gradient(135deg, #2dd4bf, #14b8a6) !important;
  color: white !important;
  box-shadow: 0 4px 12px rgba(45, 212, 191, 0.4);
  transform: translateX(6px) !important;
}

.step-card.completed {
  background: rgba(45, 212, 191, 0.2);
  color: white;
  border: 1px solid rgba(45, 212, 191, 0.3);
}

.step-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.2);
  font-size: 0.875rem;
  transition: all 0.3s;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  flex-shrink: 0;
}

.step-card:hover .step-icon {
  transform: scale(1.05);
}

.step-card.active .step-icon {
  background: rgba(255, 255, 255, 0.95);
  color: #0891b2;
  box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
  font-weight: 700;
}

.step-card.completed .step-icon {
  background: rgba(45, 212, 191, 0.9);
  color: white;
  box-shadow: 0 0 10px rgba(45, 212, 191, 0.5);
}

.step-icon .icon {
  width: 20px;
  height: 20px;
  fill: white;
}

.step-card.active .step-icon .icon {
  fill: #0891b2;
}

.step-card.completed .step-icon .icon {
  fill: white;
}

.step-text {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.step-title {
  font-size: 0.875rem;
  font-weight: 500;
  color: rgba(255, 255, 255, 0.9);
  transition: color 0.3s;
}

.step-card.active .step-title {
  font-weight: 700;
  color: white;
}

.step-card.completed .step-title {
  color: white;
}

.step-desc {
  font-size: 0.75rem;
  opacity: 0.8;
  color: rgba(255, 255, 255, 0.8);
}

.step-status {
  flex-shrink: 0;
}

.step-status .check {
  width: 18px;
  height: 18px;
  fill: white;
}

.step-status .arrow {
  font-size: 1.125rem;
  color: white;
  font-weight: 700;
}

/* Right Content Area */
.step-content-modern {
  flex: 1;
  padding: 2rem 2.5rem;
  padding-bottom: 5rem;
  overflow-y: auto;
  background: linear-gradient(135deg, #ecfeff 0%, #f0fdfa 50%, #ffffff 100%);
  position: relative;
}

.step-content-modern::-webkit-scrollbar {
  width: 8px;
}

.step-content-modern::-webkit-scrollbar-track {
  background: rgba(20, 184, 166, 0.08);
  border-radius: 10px;
  margin: 10px 0;
}

.step-content-modern::-webkit-scrollbar-thumb {
  background: rgba(20, 184, 166, 0.3);
  border-radius: 10px;
}

.step-content-modern::-webkit-scrollbar-thumb:hover {
  background: rgba(20, 184, 166, 0.5);
}

.step-panel-modern {
  animation: fadeSlideIn 0.4s ease;
}

@keyframes fadeSlideIn {
  from {
    opacity: 0;
    transform: translateX(20px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.panel-header {
  margin-bottom: 2rem;
  position: relative;
}

.panel-header h3 {
  font-size: 1.5rem;
  font-weight: 700;
  background: linear-gradient(135deg, #0891b2, #14b8a6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
  position: relative;
  display: inline-block;
}

.panel-header h3::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #14b8a6, #2dd4bf);
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(20, 184, 166, 0.4);
}

.panel-header p {
  color: #64748b;
  font-size: 0.938rem;
}

.btn-add-room {
  position: absolute;
  top: 0;
  right: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
}

.btn-add-room:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(20, 184, 166, 0.35);
}

.btn-add-room .icon {
  width: 18px;
  height: 18px;
  fill: white;
}

/* Form Fields */
.form-grid-modern {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1.5rem;
}

.form-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-field.full-width {
  grid-column: 1 / -1;
}

.form-field.highlight {
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  padding: 1.25rem;
  border-radius: 12px;
  border: 2px solid #99f6e4;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.08);
  transition: all 0.3s;
}

.form-field.highlight:hover {
  border-color: #5eead4;
  box-shadow: 0 6px 20px rgba(20, 184, 166, 0.12);
}

.form-label {
  font-weight: 600;
  font-size: 0.938rem;
  color: #0e7490;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.input-modern {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.9);
  border: 2px solid #99f6e4;
  border-radius: 0.75rem;
  color: #0e7490;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(20, 184, 166, 0.08);
  font-family: inherit;
}

.input-modern:focus {
  border-color: #14b8a6;
  background: white;
  box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1), 0 4px 12px rgba(20, 184, 166, 0.15);
  outline: none;
}

.input-modern:hover {
  border-color: #5eead4;
}

.input-modern::placeholder {
  color: #94a3b8;
}

.input-with-icon {
  position: relative;
  display: flex;
  align-items: center;
}

.currency-symbol {
  position: absolute;
  left: 1.125rem;
  font-weight: 700;
  color: #14b8a6;
  font-size: 1rem;
  z-index: 1;
}

.input-modern.with-prefix {
  padding-left: 3rem;
}

/* Rooms Grid */
.rooms-grid-modern {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 1.5rem;
}

.room-card-modern {
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  border: 2px solid #99f6e4;
  border-radius: 1rem;
  overflow: hidden;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.08);
}

.room-card-modern:hover {
  border-color: #5eead4;
  box-shadow: 0 6px 20px rgba(20, 184, 166, 0.15);
  transform: translateY(-2px);
}

.room-image-wrapper {
  position: relative;
  height: 160px;
  background: #ecfeff;
  overflow: hidden;
}

.room-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.room-card-modern:hover .room-image-wrapper img {
  transform: scale(1.05);
}

.room-edit-btn {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.95);
  border: 1px solid #99f6e4;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.room-edit-btn:hover {
  background: #14b8a6;
  transform: scale(1.1);
  border-color: #14b8a6;
}

.room-edit-btn .icon {
  width: 16px;
  height: 16px;
  fill: #14b8a6;
}

.room-edit-btn:hover .icon {
  fill: white;
}

.room-details {
  padding: 1.25rem;
  background: white;
}

.room-details h5 {
  font-size: 1rem;
  font-weight: 600;
  color: #0e7490;
  margin-bottom: 0.75rem;
}

.room-stats {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
  font-size: 0.875rem;
  color: #64748b;
}

.room-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.room-tags .tag {
  background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
  color: #0e7490;
  padding: 0.375rem 0.75rem;
  border-radius: 0.5rem;
  font-size: 0.75rem;
  font-weight: 600;
}

/* Amenities Grid */
.amenities-grid-modern {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
  gap: 1rem;
}

.amenity-checkbox {
  display: flex;
  align-items: center;
  gap: 0.875rem;
  padding: 1rem;
  border-radius: 0.75rem;
  cursor: pointer;
  transition: all 0.3s ease;
  background: linear-gradient(135deg, #ffffff 0%, #f0fdfa 100%);
  border: 2px solid #ccfbf1;
}

.amenity-checkbox:hover {
  background: linear-gradient(135deg, #ccfbf1 0%, #99f6e4 100%);
  border-color: #5eead4;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
}

.amenity-checkbox input {
  display: none;
}

.checkbox-box {
  width: 24px;
  height: 24px;
  border: 2px solid #99f6e4;
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s;
  flex-shrink: 0;
  background: white;
}

.amenity-checkbox input:checked + .checkbox-box {
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
  border-color: #14b8a6;
}

.checkbox-box .icon {
  width: 14px;
  height: 14px;
  fill: white;
  opacity: 0;
  transition: opacity 0.3s;
}

.amenity-checkbox input:checked + .checkbox-box .icon {
  opacity: 1;
}

.checkbox-label {
  font-weight: 500;
  font-size: 0.938rem;
  color: #0e7490;
}

/* Modal Actions Footer */
.modal-actions {
  position: absolute;
  bottom: -1;
  left: 50%;
  transform: translateX(-50%);
  width: 60%;
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  border: none;
  border-radius: 8px;
  padding: 1rem 1.5rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  box-shadow: 0 8px 20px rgba(20, 184, 166, 0.3);
  z-index: 10;
  margin-top: 3rem;
}

.action-group {
  display: flex;
  gap: 0.75rem;
  align-items: center;
}

.btn-ghost {
  padding: 0.75rem 1.25rem;
  background: white;
  color: #0891b2;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-ghost:hover {
  background: #f0fdfa;
  color: #0e7490;
  transform: translateY(-1px);
}

.btn-secondary-modern {
  padding: 0.75rem 1.25rem;
  background: transparent;
  border: 2px solid white;
  color: white;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-secondary-modern:hover {
  background: rgba(255, 255, 255, 0.1);
  transform: translateY(-1px);
}

.btn-primary-modern {
  padding: 0.75rem 1.25rem;
  background: white;
  color: #0891b2;
  border: none;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.9rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.btn-primary-modern:hover {
  background: #f0fdfa;
  color: #0e7490;
  transform: translateY(-1px);
}

.btn-primary-modern:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
}

.btn-primary-modern .icon,
.btn-secondary-modern .icon {
  width: 18px;
  height: 18px;
  fill: currentColor;
}

/* Room Edit Modal */
.modal-overlay-room-edit {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.4);
  backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 2000;
  padding: 2rem;
  animation: fadeInModal 0.3s ease;
}

.modal-room-edit {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 1.5rem;
  width: 100%;
  max-width: 800px;
  max-height: 90vh;
  overflow: hidden;
  border: 1px solid rgba(6, 182, 212, 0.2);
  box-shadow: 0 0 60px rgba(6, 182, 212, 0.15), 0 20px 25px -5px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  position: relative;
  animation: slideUpModal 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-room-edit::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: linear-gradient(90deg, #06b6d4, #14b8a6, #2dd4bf);
  animation: shimmer 3s linear infinite;
  z-index: 10;
}

.modal-close-room {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  background: white;
  border: 1px solid #E0E0E0;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: var(--transition);
  z-index: 10;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.modal-close-room:hover {
  background: #F5F5F5;
  transform: scale(1.1);
}

.modal-close-room .icon {
  width: 18px;
  height: 18px;
  fill: #666;
}

.room-edit-loading {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  gap: 1.5rem;
}

.room-edit-loading p {
  color: #64748b;
  font-size: 1rem;
  font-weight: 500;
}

.room-edit-content {
  display: flex;
  flex-direction: column;
  height: 100%;
  max-height: 90vh;
}

.room-edit-header {
  padding: 2rem 2.5rem 1.5rem;
  border-bottom: 1px solid #ccfbf1;
  background: white;
  flex-shrink: 0;
}

.room-edit-header h3 {
  font-size: 1.625rem;
  font-weight: 700;
  background: linear-gradient(135deg, #0891b2, #14b8a6);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-bottom: 0.5rem;
}

.room-edit-header h3::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, #14b8a6, #2dd4bf);
  border-radius: 2px;
}

.room-edit-header p {
  color: #64748b;
  font-size: 0.938rem;
}

.room-edit-body {
  flex: 1;
  padding: 2rem 2.5rem 1.5rem;
  overflow-y: auto;
  background: linear-gradient(135deg, #ecfeff 0%, #f0fdfa 50%, #ffffff 100%);
  margin-bottom: 80px;
}

.room-edit-body::-webkit-scrollbar {
  width: 6px;
}

.room-edit-body::-webkit-scrollbar-track {
  background: rgba(20, 184, 166, 0.08);
  border-radius: 10px;
}

.room-edit-body::-webkit-scrollbar-thumb {
  background: rgba(20, 184, 166, 0.3);
  border-radius: 10px;
}

.room-edit-body::-webkit-scrollbar-thumb:hover {
  background: rgba(20, 184, 166, 0.5);
}

/* Image Management */
.room-image-management {
  margin-top: 2rem;
  padding-top: 2rem;
  border-top: 2px solid #ccfbf1;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1.5rem;
}

.section-header label {
  font-weight: 600;
  font-size: 1rem;
  color: #0e7490;
}

.btn-add-image {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.625rem 1rem;
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
}

.btn-add-image:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(20, 184, 166, 0.35);
}

.btn-add-image .icon {
  width: 16px;
  height: 16px;
  fill: white;
}

.current-images-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.image-card {
  position: relative;
  aspect-ratio: 1;
  border-radius: 10px;
  overflow: hidden;
  border: 2px solid #99f6e4;
  box-shadow: 0 4px 10px rgba(20, 184, 166, 0.15);
  transition: all 0.3s ease;
  background: #ecfeff;
}

.image-card:hover {
  border-color: #5eead4;
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(20, 184, 166, 0.2);
}

.image-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.image-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, rgba(0, 0, 0, 0.6) 0%, transparent 100%);
  opacity: 0;
  transition: opacity 0.3s;
  display: flex;
  gap: 0.5rem;
  padding: 0.5rem;
  justify-content: flex-end;
}

.image-card:hover .image-overlay {
  opacity: 1;
}

.btn-remove-image,
.btn-replace-image {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  border: none;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

.btn-remove-image {
  background: rgba(231, 76, 60, 0.95);
}

.btn-remove-image:hover {
  background: #e74c3c;
  transform: scale(1.1);
}

.btn-replace-image {
  background: rgba(52, 152, 219, 0.95);
}

.btn-replace-image:hover {
  background: #3498db;
  transform: scale(1.1);
}

.btn-remove-image .icon,
.btn-replace-image .icon {
  width: 16px;
  height: 16px;
  fill: white;
}

.new-images-section {
  margin-top: 1.5rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  border-radius: 12px;
  border: 2px solid #99f6e4;
}

.new-images-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 600;
  font-size: 0.938rem;
  color: #0e7490;
  margin-bottom: 1rem;
}

.new-images-label .icon {
  width: 18px;
  height: 18px;
  fill: #14b8a6;
}

.new-images-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
  gap: 1rem;
}

.image-card.new {
  border-color: #14b8a6;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.2);
}

.new-badge {
  position: absolute;
  bottom: 0.5rem;
  right: 0.5rem;
  background: linear-gradient(135deg, #14b8a6, #2dd4bf);
  color: white;
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.625rem;
  font-weight: 700;
}

.empty-images-state {
  text-align: center;
  padding: 3rem 2rem;
  background: linear-gradient(135deg, #f0fdfa, #ffffff);
  border-radius: 12px;
  border: 3px dashed #99f6e4;
}

.empty-icon {
  width: 64px;
  height: 64px;
  fill: #14b8a6;
  opacity: 0.5;
  margin: 0 auto 1rem;
}

.empty-images-state p {
  color: #64748b;
  font-size: 0.938rem;
  margin-bottom: 1.5rem;
}

.room-edit-footer-sticky {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 1.5rem 2.5rem;
  border-top: 1px solid #ccfbf1;
  background: white;
  display: flex;
  justify-content: flex-end;
  gap: 1rem;
  box-shadow: 0 -4px 12px rgba(20, 184, 166, 0.1);
  z-index: 10;
}

.button-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid #14b8a6;
  border-top-color: transparent;
  border-radius: 50%;
  animation: spin 0.6s linear infinite;
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
  border-radius: 0.75rem;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
  border-left: 4px solid #14b8a6;
  z-index: 3000;
  min-width: 320px;
  max-width: 420px;
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
  border-left-color: #14b8a6;
}

.toast-notification.error {
  border-left-color: #ef4444;
}

.toast-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toast-icon .icon {
  width: 20px;
  height: 20px;
  fill: white;
}

.toast-content {
  flex: 1;
}

.toast-message {
  color: #0e7490;
  font-weight: 600;
  font-size: 0.938rem;
  line-height: 1.4;
}

.toast-fade-enter-active,
.toast-fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.toast-fade-enter-from {
  opacity: 0;
  transform: translateX(100px);
}

.toast-fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .properties-grid {
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  }
}

@media (max-width: 1024px) {
  .hero-title {
    font-size: 2.5rem;
  }
  
  .stats-bar {
    flex-wrap: wrap;
  }
  
  .properties-grid {
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.75rem;
  }
  
  .modal-wizard {
    max-width: 95vw;
  }

  .modal-body-modern {
    flex-direction: column;
  }

  .step-sidebar {
    width: 100%;
    max-height: 150px;
    border-right: none;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    overflow-x: auto;
    display: flex;
    padding: 1rem;
  }

  .step-card {
    flex-shrink: 0;
    min-width: 200px;
  }

  .step-content-modern {
    max-height: calc(90vh - 400px);
  }
}

@media (max-width: 768px) {
  .hero-header {
    padding: 3rem 1.5rem 4rem;
  }
  
  .hero-title {
    font-size: 2rem;
  }
  
  .hero-subtitle {
    font-size: 1.125rem;
  }
  
  .stats-bar {
    padding: 1.25rem 1.5rem;
    gap: 1.5rem;
  }
  
  .main-content {
    padding: 0 1.5rem 3rem;
  }
  
  .properties-grid {
    grid-template-columns: 1fr;
  }
  
  .form-grid-modern {
    grid-template-columns: 1fr;
  }
  
  .fab {
    bottom: 1.5rem;
    right: 1.5rem;
  }
  
  .fab-label {
    display: none;
  }
  
  .modal-overlay-modern,
  .modal-overlay-room-edit {
    padding: 1rem;
  }
  
  .modal-wizard,
  .modal-room-edit {
    max-height: 95vh;
  }
  
  .modal-header-modern,
  .room-edit-header,
  .step-content-modern,
  .room-edit-body {
    padding: 1.5rem;
  }

  .modal-actions {
    width: 90%;
    flex-direction: column;
    gap: 1rem;
  }

  .action-group {
    width: 100%;
    flex-direction: column;
  }

  .btn-ghost,
  .btn-secondary-modern,
  .btn-primary-modern {
    width: 100%;
    justify-content: center;
  }

  .toast-notification {
    bottom: 1rem;
    right: 1rem;
    left: 1rem;
    min-width: auto;
  }

  .current-images-grid,
  .new-images-grid {
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
  }

  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }

  .btn-add-image {
    width: 100%;
    justify-content: center;
  }

  .room-edit-footer-sticky {
    flex-direction: column;
  }

  .btn-ghost,
  .btn-primary-modern {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero-title {
    font-size: 1.75rem;
  }
  
  .stats-bar {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .stat-divider {
    display: none;
  }
  
  .card-content {
    padding: 1.25rem;
  }
  
  .property-name {
    font-size: 1.125rem;
  }
  
  .price-amount {
    font-size: 1.5rem;
  }
  
  .modal-progress-bar {
    padding: 1.5rem 1.5rem 0;
  }

  .progress-indicators {
    gap: 0.5rem;
  }

  .progress-dot {
    width: 30px;
    height: 30px;
    font-size: 0.75rem;
  }

  .rooms-grid-modern {
    grid-template-columns: 1fr;
  }

  .current-images-grid,
  .new-images-grid {
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  }
}

/* Additional Rooms Section */
.additional-section {
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 3px dashed #14b8a6;
}

.additional-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 1.5rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
  border-radius: 12px;
  border: 2px solid #99f6e4;
}

.room-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #0891b2;
  margin: 0;
}

.additional-subtitle {
  color: #64748b;
  font-size: 0.875rem;
  margin-top: 0.5rem;
}

.btn-add-extra {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.25rem;
  background: linear-gradient(135deg, #14b8a6, #0891b2);
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  font-size: 0.875rem;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.25);
  white-space: nowrap;
}

.btn-add-extra:hover {
  background: linear-gradient(135deg, #0891b2, #06b6d4);
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(20, 184, 166, 0.35);
}

.btn-add-extra .icon {
  flex-shrink: 0;
}

.empty-additional {
  text-align: center;
  padding: 3rem 2rem;
  background: linear-gradient(135deg, #f0fdfa, #ffffff);
  border-radius: 12px;
  border: 3px dashed #ccfbf1;
  margin-bottom: 1.5rem;
}

.empty-icon {
  color: #99f6e4;
  margin-bottom: 1rem;
}

.empty-additional p {
  color: #64748b;
  font-size: 1rem;
  margin-bottom: 0.5rem;
}

.empty-hint {
  color: #94a3b8;
  font-size: 0.875rem !important;
}

.additional-rooms-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.additional-room-card {
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  border: 2px solid #99f6e4;
  border-radius: 12px;
  padding: 1.5rem;
  transition: all 0.3s ease;
}

.additional-room-card:hover {
  border-color: #5eead4;
  box-shadow: 0 4px 12px rgba(20, 184, 166, 0.15);
  transform: translateY(-2px);
}

.additional-room-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #ccfbf1;
}

.additional-room-number {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0891b2;
  margin: 0;
}

.btn-remove-extra {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: #fee2e2;
  border: 1px solid #fecaca;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: all 0.3s ease;
}

.btn-remove-extra:hover {
  background: #ef4444;
  border-color: #dc2626;
  transform: scale(1.1);
}

.btn-remove-extra svg {
  color: #ef4444;
}

.btn-remove-extra:hover svg {
  color: white;
}

.form-group {
  margin-bottom: 1rem;
}

.form-label {
  display: block;
  font-weight: 600;
  font-size: 0.875rem;
  color: #0e7490;
  margin-bottom: 0.5rem;
}

.form-input {
  width: 100%;
  padding: 0.75rem 1rem;
  background: rgba(255, 255, 255, 0.9);
  border: 2px solid #99f6e4;
  border-radius: 0.75rem;
  color: #0e7490;
  font-size: 0.95rem;
  font-weight: 500;
  transition: all 0.3s ease;
  box-shadow: 0 2px 6px rgba(20, 184, 166, 0.08);
  font-family: inherit;
}

.form-input:focus {
  border-color: #14b8a6;
  background: white;
  box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.1), 0 4px 12px rgba(20, 184, 166, 0.15);
  outline: none;
}

.file-input {
  padding: 0.625rem;
  cursor: pointer;
}

.image-preview {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
  gap: 0.75rem;
  margin-top: 1rem;
}

.preview-img {
  width: 100%;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #99f6e4;
}

/* Enhanced Loading Spinner */
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
  animation: spinMedium 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  animation-direction: reverse;
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
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes spinMedium {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

@keyframes spinSlow {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.loading-text {
  color: #0891b2;
  font-size: 1.125rem;
  font-weight: 600;
  text-align: center;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.6;
  }
}


/* Save Loading Overlay */
.save-loading-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(255, 255, 255, 0.98);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  animation: fadeInOverlay 0.3s ease;
}

@keyframes fadeInOverlay {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.save-loading-content {
  text-align: center;
  padding: 3rem;
  background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%);
  border-radius: 20px;
  border: 2px solid #99f6e4;
  box-shadow: 0 20px 60px rgba(20, 184, 166, 0.2);
  animation: scaleIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes scaleIn {
  from {
    transform: scale(0.9);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.loading-subtext {
  color: #64748b;
  font-size: 0.938rem;
  margin-top: 0.5rem;
  font-weight: 500;
}


</style>