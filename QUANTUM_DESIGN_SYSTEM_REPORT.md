# QUANTUM Design System - Auditoría Completa

## 1. PALETA DE COLORES

### Colores Primarios (Gradientes)
```
Quantum (Electric Blue) - Primario
  - RGB: hsl(195, 100%, 50%)
  - HEX: #00BFFF
  - Tonalidades: quantum-50 a quantum-900
  - Uso: Botones principales, elementos activos, gradientes primarios

Void (Cosmic Purple) - Secundario
  - RGB: hsl(270, 80%, 60%)
  - HEX: #9D5CFF
  - Tonalidades: void-50 a void-900
  - Uso: Acentos, elementos decorativos, gradientes secundarios

Photon (Energetic Gold) - Accent
  - RGB: hsl(45, 100%, 55%)
  - HEX: #FFD700
  - Tonalidades: photon-50 a photon-900
  - Uso: Elementos de éxito, acentos especiales, destacados
```

### Fondos y Superficies
```
Space (Deepest Dark)
  - space-500: #0A0A0F (Fondo principal de body)
  - space-600 a 900: Variaciones más oscuras

Matter (Dark Matter)
  - matter (DEFAULT): #15151F (Color principal de cards)
  - matter-light: #1F1F2E (Hover states, bordes, divisores)
  
Uso: Componentes principales, sidebars, cards
```

### Colores Semánticos
```
Verde (Success):
  - bg-green-500 / text-green-300 / border-green-500/30
  - Uso: Estados activos, confirmaciones, checkmarks

Amarillo (Warning):
  - bg-yellow-500 / text-yellow-300 / border-yellow-500/30
  - Uso: Estados inactivos, advertencias, precaución

Rojo (Error):
  - bg-red-500 / text-red-300 / border-red-500/30
  - Uso: Eliminación, errores, estados críticos

Gris (Neutral):
  - gray-300 a gray-500: Textos secundarios
  - gray-600 a gray-700: Textos deshabilitados
```

---

## 2. TIPOGRAFÍA

### Font Family
```
Primary: Inter (300, 400, 500, 600, 700, 800, 900)
Display: Geist (para títulos especiales)
Mono: Geist Mono (código, valores)
```

### Tamaños Base
```
xs:   0.75rem  (12px) - Badges, labels pequeños
sm:   0.875rem (14px) - Texto secundario
base: 1rem     (16px) - Texto normal
lg:   1.125rem (18px) - Texto destacado
xl:   1.25rem  (20px) - Subtítulos
2xl:  1.5rem   (24px) - Títulos
3xl:  1.875rem (30px) - Secciones
4xl:  2.25rem  (36px) - Páginas
5xl:  3rem     (48px) - Headers principales
```

### Pesos
```
font-medium: 500 (Defecto para labels, botones)
font-semibold: 600 (Títulos, destacados)
font-bold: 700 (Headers principales)
```

---

## 3. ESPACIADO Y LAYOUT

### Grid System
```
Sidebar:
  - Expandido: w-64 (256px)
  - Colapsado: w-20 (80px)
  - Transición: 300ms ease-in-out

Main Content:
  - Padding: p-4 sm:p-6 lg:p-8
  - Max Width: None (full width)
  - Responsive: Flex wrapping en mobile

Cards Grid:
  - Desktop: grid-cols-1 lg:grid-cols-2 xl:grid-cols-3
  - Tablet: grid-cols-1 md:grid-cols-2
  - Gap: gap-6
```

### Componente Spacing
```
Form Elements:
  - Input height: h-10 (py-3)
  - Gap between inputs: space-y-6
  - Label margin: mb-2

Cards:
  - Padding: p-6
  - Section margins: mb-6, mb-8
  - Border spacing: border-matter-light
```

---

## 4. BORDES Y RADIOS

### Border Radius
```
quantum: 12px
quantum-lg: 16px
quantum-xl: 20px
```

### Bordes
```
Color: border-matter-light
Espesor: border (1px por defecto)
Efecto hover: border-quantum-500/30
Transición: transition-all duration-200
```

---

## 5. SOMBRAS Y EFECTOS

### Box Shadows
```
quantum:    '0 4px 20px rgba(0, 191, 255, 0.15)'
quantum-lg: '0 10px 40px rgba(0, 191, 255, 0.2)'
void:       '0 4px 20px rgba(157, 92, 255, 0.15)'
glow:       '0 0 20px rgba(0, 191, 255, 0.4)'
```

### Efectos Especiales

#### Glassmorphism
```
backdrop-blur-quantum (12px)
bg-matter/40 o bg-matter/80
border border-matter-light
Uso: Modales, overlays, cards flotantes
```

#### Glow Effects
```
glow-quantum: box-shadow con brillo azul
glow-void: box-shadow con brillo púrpura
Animación: hover y estados activos
```

#### Degradados
```
Horizontal: from-quantum-500 to-void-500
Vertical: from-quantum-500 to-void-500 (para elementos verticales)
Radial: para fondos de cards
Opacity: /10, /20, /30 para variaciones sutiles
```

---

## 6. ANIMACIONES Y TRANSICIONES

### Transiciones Base
```
Duración: duration-200 (rápido), duration-300 (medio), duration-500 (lento)
Easing: ease-in-out, ease-out
Propiedad: transition-all
```

### Animaciones Keyframe
```
fadeIn:    0ms → 300ms, opacity 0 → 1
slideUp:   0ms → 300ms, translateY(10px) → 0, opacity 0 → 1
slideDown: 0ms → 300ms, translateY(-10px) → 0, opacity 0 → 1
scaleIn:   0ms → 200ms, scale(0.95) → 1, opacity 0 → 1
pulse-slow: 3s infinite (para elementos destacados)
glow:      2s infinite, shadow animado
```

### Transiciones en Interacciones
```
Hover buttons:     hover:scale-105
Active buttons:    active:scale-95
Hover cards:       hover:scale-105 hover:shadow-quantum-lg
Hover sidebar:     hover:bg-matter-light
Focus inputs:      focus:ring-2 focus:ring-quantum-500
```

---

## 7. COMPONENTES REUTILIZABLES

### BOTONES

#### btn-quantum (Principal)
```html
<button class="btn-quantum">
  px-6 py-3 rounded-quantum font-medium
  bg-gradient-to-r from-quantum-500 to-quantum-600
  text-white shadow-quantum hover:shadow-quantum-lg
  hover:scale-105 active:scale-95
</button>
```

#### btn-void (Secundario)
```html
<button class="btn-void">
  px-6 py-3 rounded-quantum font-medium
  bg-gradient-to-r from-void-500 to-void-600
  text-white shadow-void hover:shadow-lg
  hover:scale-105 active:scale-95
</button>
```

#### btn-ghost (Terciario)
```html
<button class="btn-ghost">
  px-6 py-3 rounded-quantum font-medium
  bg-matter/50 backdrop-blur-sm border border-matter-light
  text-gray-200 hover:bg-matter-light hover:text-white
</button>
```

#### Tamaños
```
.btn-sm:   px-4 py-2 text-sm
.btn-lg:   px-8 py-4 text-lg
Base:      px-6 py-3
```

---

### CARDS

#### card-quantum (Estándar)
```html
<div class="card-quantum">
  bg-matter/80 backdrop-blur-quantum
  border border-matter-light rounded-quantum-lg
  shadow-lg transition-all duration-300
  hover:shadow-quantum hover:border-quantum-500/30
</div>
```

#### card-glass (Glassmorphism)
```html
<div class="card-glass">
  bg-matter/40 backdrop-blur-quantum
  border border-white/10 rounded-quantum-lg
  shadow-2xl
</div>
```

---

### INPUTS

#### input-quantum
```html
<input class="input-quantum" />
w-full px-4 py-3 rounded-quantum
bg-matter border border-matter-light
text-gray-100 placeholder-gray-500
focus:outline-none focus:ring-2 focus:ring-quantum-500
```

#### select-quantum
```html
<select class="select-quantum"></select>
(mismo que input-quantum + cursor-pointer)
```

---

### BADGES Y TAGS

#### badge-quantum
```html
<span class="badge-quantum">
  bg-quantum-500/20 text-quantum-300 border border-quantum-500/30
  inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
</span>
```

#### badge-success / warning / error
```
Mismo patrón que badge-quantum con colores respectivos
```

---

### STATUS CHIPS (Proyectos)

#### Activos
```
bg-green-500/20 text-green-300 border-green-500/30
hover:bg-green-500/30 hover:shadow-lg hover:shadow-green-500/20
```

#### Inactivos
```
bg-gray-500/20 text-gray-300 border-gray-500/30
hover:bg-gray-500/30 hover:shadow-lg hover:shadow-gray-500/20
```

#### Cerrados
```
bg-red-500/20 text-red-300 border-red-500/30
hover:bg-red-500/30 hover:shadow-lg hover:shadow-red-500/20
```

---

## 8. TABLA DE DATOS

### table-quantum
```html
<table class="table-quantum">
  thead: bg-matter border-b border-matter-light
  th:    px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase
  tbody tr: border-b border-matter-light hover:bg-matter-light/50
  td:    px-6 py-4 text-sm text-gray-300
</table>
```

---

## 9. SIDEBAR Y NAVEGACIÓN

### Sidebar Quantum
```
Position: fixed left-0 top-0 h-screen
Background: bg-matter/95 backdrop-blur-quantum border-r border-matter-light
Estados:
  - Expandido: w-64
  - Colapsado: w-20
  - Mobile: translate-x-0 o -translate-x-full

Iconografía: SVG inline (w-5 h-5)
Active item: bg-gradient-to-r from-quantum-500/20 to-void-500/20
           border border-quantum-500/30 text-white shadow-quantum
Hover item:  bg-matter-light text-white
```

### Top Bar
```
Position: sticky top-0 z-40
Background: bg-matter/95 backdrop-blur-quantum border-b border-matter-light
Elementos:
  - Mobile menu button
  - Page title
  - Search bar
  - Notifications
  - Profile dropdown
```

---

## 10. FORMULARIOS Y MODALES

### Modal Dialog
```
Backdrop: bg-black/60 backdrop-blur-sm
Container: bg-matter border border-matter-light rounded-quantum-xl
Transiciones: 
  - Enter: ease-out duration-300
  - Leave: ease-in duration-200
  - Scale: 95 → 100
```

### Form Groups
```
spacing: space-y-6
Label: text-sm font-medium text-gray-300 mb-2
Input: class="input-quantum"
Error: text-red-400 text-xs
```

---

## 11. TOAST NOTIFICATIONS

### Toast System
```
Position: fixed top-4 right-4 z-50
Contenedor: space-y-2 pointer-events-none

Tipos:
  - success:  bg-green-500/90 border-green-400/50
  - error:    bg-red-500/90 border-red-400/50
  - warning:  bg-yellow-500/90 border-yellow-400/50
  - info:     bg-quantum-500/90 border-quantum-400/50

Animación: slide-down (300ms ease-out)
Auto-close: 4000ms
```

---

## 12. DECORATIVOS Y ELEMENTOS VISUALES

### Gradientes Decorativos
```
Card headers: bg-gradient-to-r from-matter via-matter-light to-matter
Blobs blur: w-32 h-32 bg-quantum-500/10 rounded-full blur-3xl
Líneas: w-1 h-6 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full
Pattern: SVG patterns con gradientes lineales
```

### Iconografía
```
SVG Heroicons (outline)
Tamaños: w-4 h-4, w-5 h-5, w-6 h-6
Color: inherit from parent o text-{color}-400/300
Hover: transition-transform group-hover:scale-110
```

---

## 13. PROPIEDADES RESPONSIVE

### Breakpoints
```
Mobile:  default (0px)
Tablet:  md: 768px
Desktop: lg: 1024px, xl: 1280px
```

### Patrones Comunes
```
Grid: grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4
Spacing: px-4 sm:px-6 lg:px-8
Display: hidden md:flex, hidden sm:block
Width: w-full md:w-64, w-64
```

---

## 14. ESTADO DE LAS VISTAS EXISTENTES

### Completas y Elegantes (Quantum Design)
- **login.blade.php**: 100% Quantum + Glassmorphism + Animaciones
- **dashboard.blade.php**: 100% Quantum con cards KPI y estadísticas
- **proyectos/index.blade.php**: 100% Quantum con grid de proyectos
- **estadistica.blade.php**: 100% Quantum con métricas y charts

### Incompletas o Sin Estilos Quantum (Bootstrap)
- **auditoria.blade.php**: Bootstrap clásico, sin Quantum
- **usuarios/index.blade.php**: Bootstrap clásico, sin Quantum

---

## 15. GUÍA PARA COMPLETAR VISTAS FALTANTES

### Estructura Base para Nueva Vista
```html
@extends('layouts.quantum')

@section('page-title', 'Nombre de Página')

@section('content')

<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-quantum-500 to-void-500 rounded-full"></div>
                Nombre de Página
            </h1>
            <p class="text-gray-400">Descripción o subtítulo</p>
        </div>
        <!-- Botón de acción si aplica -->
        <a href="#" class="btn-quantum">Acción Principal</a>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- KPI Cards aquí -->
    </div>
</div>

<!-- Main Content -->
<div class="card-quantum p-6">
    <!-- Contenido principal -->
</div>

@endsection
```

### Componentes Estándar por Vista

#### Para Auditoría:
1. Header con título y filtros
2. Cards KPI: Eventos totales, últimos 24h, eventos por tipo
3. Tabla con eventos, usuario, IP, timestamp
4. Filtros: tipo de evento, rango de fechas
5. Export button

#### Para Usuarios:
1. Header con botón "Nuevo Usuario"
2. Cards KPI: Total usuarios, activos hoy, roles activos
3. Tabla de usuarios con: nombre, email, rol, fecha registro
4. Acciones: editar, cambiar contraseña, eliminar
5. Modales para crear/editar usuarios
6. Search y pagination

#### Para Estadísticas:
1. Time range selector (7d, 30d, 90d)
2. KPI Cards: Total, activos, valor, éxito
3. Charts: Donut, líneas, barras
4. Métricas adicionales
5. Export reports

---

## 16. CHECKLIST DE IMPLEMENTACIÓN

```
Para cada vista nueva:

[ ] Usar layout 'quantum.blade.php'
[ ] Header con gradiente decorativo
[ ] Cards KPI con iconos y colores
[ ] Componentes con clases Quantum:
    - btn-quantum, btn-ghost
    - card-quantum
    - input-quantum, select-quantum
    - badge-quantum
[ ] Espaciado coherente: mb-6, mb-8, gap-6
[ ] Hover effects: hover:scale-105, hover:shadow-quantum-lg
[ ] Transiciones: transition-all duration-200
[ ] Responsive: grid-cols-1 md:grid-cols-2 lg:grid-cols-3
[ ] Iconografía SVG Heroicons
[ ] Colores semánticos (green, red, yellow)
[ ] Dark theme aplicado (text-white, bg-matter)
[ ] Animaciones en carga (fade-in, slide-up)
[ ] Toasts para feedback
[ ] Mobile friendly
```

---

## 17. ARCHIVOS CSS RELEVANTES

```
/resources/css/app.css           → Componentes principales
/resources/css/variables.css     → Variables CSS base
/resources/css/base.css          → Reset y estilos globales
/resources/css/layouts/sidebar.css
/resources/css/layouts/content.css
/resources/css/components/      → Componentes específicos
```

---

## 18. EJEMPLO: KPI CARD COMPLETA

```html
<div class="card-quantum p-6 relative overflow-hidden group">
    <!-- Decorative blur blob -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-quantum-500/10 rounded-full blur-3xl group-hover:bg-quantum-500/20 transition-all duration-500"></div>
    
    <div class="relative">
        <!-- Icon + Label -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-quantum bg-quantum-500/20 border border-quantum-500/30 flex items-center justify-center">
                    <svg class="w-5 h-5 text-quantum-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <!-- Icon SVG -->
                    </svg>
                </div>
                <span class="text-xs font-medium text-gray-400 uppercase tracking-wider">Label</span>
            </div>
            <span class="text-xs font-semibold text-green-400">+12%</span>
        </div>
        
        <!-- Number + Description -->
        <div class="mb-2">
            <span class="text-4xl font-bold text-white">123</span>
        </div>
        <p class="text-sm text-gray-400">Descripción</p>
        
        <!-- Progress bar or mini chart -->
        <div class="mt-4 h-1 bg-matter-light rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-quantum-500 to-void-500 rounded-full"
                 style="width: 75%"></div>
        </div>
    </div>
</div>
```

---

## CONCLUSIONES

El sistema QUANTUM es **altamente sofisticado y coherente**:

- Color scheme: Degradados quantum-void-photon
- Dark theme: space → matter backgrounds
- Animaciones: suaves y funcionales (200-500ms)
- Componentes: reutilizables y componibles
- Responsive: mobile-first approach
- Efectos: glassmorphism, glow, blur, gradients

**Para mantener la elegancia**:
1. Siempre usar `card-quantum` para contenedores
2. Gradientes para elementos destacados
3. Transiciones de 200-300ms
4. Hover con scale y shadow
5. Colores semánticos para estados
6. SVG iconografía
7. Espaciado coherente (múltiplos de 4)

