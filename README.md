# FCPN - Informática: Digitalización de Datos Estadísticos

**Proyecto de Digitalización de Datos Históricos (1992-2023)**  
Facultad de Ciencias Puras y Naturales (FCPN) - Carrera de Informática  
Universidad Mayor de San Andrés (UMSA) - La Paz, Bolivia

---

## 📋 Descripción General

Sistema web para consulta, análisis y exportación de datos estadísticos históricos de la carrera de Informática de la FCPN. Incluye dashboards interactivos con gráficos dinámicos, reportes por dimensión con filtros, y exportación de datos a CSV.

### Stack Tecnológico
- **Backend:** PHP 8+ (nativo, sin frameworks)
- **Base de Datos:** MySQL 5.7+ / MariaDB 10.3+
- **Frontend:** HTML5, Bootstrap 5, Chart.js
- **Servidor:** Apache (XAMPP/WAMP)

---

## 📁 Estructura del Proyecto

```
fcpn_informatica/
├── config/
│   └── database.php              ← Conexión PDO centralizada
├── models/
│   ├── Model.php                 ← Clase base
│   ├── MatriculadosModel.php
│   ├── GeneroModel.php
│   ├── EstadoCivilModel.php
│   ├── ColegioModel.php
│   ├── SituacionLaboralModel.php
│   ├── JornadaModel.php
│   ├── ViviendaModel.php
│   ├── EdadModel.php
│   ├── PermanenciaModel.php
│   └── ResumenAnualModel.php
├── controllers/
│   ├── DashboardController.php   ← Lógica del dashboard
│   └── ReporteController.php     ← Lógica de reportes
├── views/
│   ├── layout/
│   │   ├── header.php
│   │   └── footer.php
│   ├── dashboard/
│   │   └── index.php             ← Dashboard principal
│   └── reportes/
│       └── index.php             ← Vista de reportes
├── public/
│   ├── index.php                 ← Router principal
│   ├── api.php                   ← Endpoints JSON
│   ├── export.php                ← Exportación de datos
│   ├── css/
│   │   └── styles.css            ← Estilos personalizados
│   └── js/
│       └── charts.js             ← Utilidades JavaScript
├── exports/                      ← Carpeta para archivos generados
├── fcpn_informatica_db.sql       ← Script de base de datos
├── PROMPT_GITHUB_COPILOT.md      ← Documentación del proyecto
└── README.md                     ← Este archivo
```

---

## 🚀 Instalación y Configuración

### 1. Requisitos Previos
- Apache (XAMPP, WAMP, o servidor local)
- MySQL 5.7+ o MariaDB 10.3+
- PHP 8.0+
- Git (opcional)

### 2. Pasos de Instalación

#### Opción A: Usando XAMPP (Recomendado)

```bash
# 1. Clonar o descargar el proyecto en htdocs
cd c:\xampp\htdocs\fcpn_informatica

# 2. Crear la base de datos
# - Abrir phpMyAdmin: http://localhost/phpmyadmin
# - Crear nueva BD: "fcpn_informatica"
# - Importar archivo: fcpn_informatica_db.sql

# 3. Verificar config/database.php
# - Asegurar que host, usuario, contraseña sean correctos

# 4. Acceder a la aplicación
# - Abrir navegador: http://localhost/fcpn_informatica/public/
```

#### Opción B: Línea de Comandos

```bash
# 1. En la carpeta del proyecto
mysql -u root -p < fcpn_informatica_db.sql

# 2. Iniciar servidor PHP
cd public
php -S localhost:8000
# Acceder a: http://localhost:8000
```

### 3. Configurar Base de Datos

En `config/database.php`, ajustar según tu entorno:

```php
$pdo = new PDO(
    'mysql:host=localhost;dbname=fcpn_informatica;charset=utf8mb4',
    'root',      // Tu usuario MySQL
    '',          // Tu contraseña (dejar vacío si no hay)
    [...]
);
```

---

## 📊 Funcionalidades Principales

### 1. Dashboard
- **4 KPIs principales:**
  - Total de Matriculados (año actual)
  - Nuevos Inscritos
  - Porcentaje de participación femenina
  - Porcentaje de estudiantes que trabajan

- **2 Gráficos interactivos:**
  - Evolución de matrículas (1992-2023)
  - Distribución por género (barras apiladas)

- **Tabla de resumen anual** con todos los datos consolidados

### 2. Reportes por Dimensión
Consultar detalladamente cada dimensión:
- Matriculados
- Nuevos Inscritos
- Género
- Estado Civil
- Colegio de Procedencia
- Situación Laboral
- Jornada Laboral
- Tipo de Vivienda
- Distribución por Edad (2023)
- Permanencia en la Carrera

**Funciones:**
- Filtrado por año o rango de años
- Exportación a CSV
- Visualización en tabla interactiva

### 3. API JSON
Endpoint para obtener datos en formato JSON:

```bash
# Obtener todos los datos de una tabla
GET /api.php?tabla=matriculados

# Obtener datos de un año específico
GET /api.php?tabla=genero&anio=2023

# Respuesta
{
    "success": true,
    "tabla": "matriculados",
    "data": [...],
    "total": 12
}
```

**Tablas disponibles:**
- matriculados
- nuevos_inscritos
- genero
- estado_civil
- colegio_procedencia
- situacion_laboral
- jornada_laboral
- vivienda
- distribucion_edad
- permanencia
- resumen_anual

---

## 💡 Uso de la Aplicación

### Acceder al Dashboard
1. Abrir navegador: `http://localhost/fcpn_informatica/public/`
2. Ver KPIs y gráficos del año actual (2023)
3. Explorar tabla de resumen consolidado

### Consultar Reportes
1. Ir a **Reportes** en la barra de navegación
2. Seleccionar dimensión a consultar (dropdown)
3. Opcionalmente: Filtrar por año (aún no implementado en UI)
4. Exportar datos a CSV si lo deseas

### Consumir API JSON
```bash
# Desde JavaScript
fetch('/api.php?tabla=matriculados')
    .then(res => res.json())
    .then(data => console.log(data.data));

# Desde cURL
curl "http://localhost/fcpn_informatica/public/api.php?tabla=genero"
```

---

## 🔒 Seguridad

- ✅ **Prepared Statements:** Todas las consultas usan placeholders (`?`) para prevenir SQL Injection
- ✅ **Escape de HTML:** Salidas HTML escapadas con `htmlspecialchars()`
- ✅ **Validación de entrada:** Tablas permitidas verificadas contra whitelist
- ✅ **Manejo de errores:** Try/catch con logging en producción
- ⚠️ **NOTA:** En producción, implementar autenticación y autorización

---

## 📈 Datos Disponibles

### Período Cubierto
- Años: 1992, 1997, 2002, 2007, 2012, 2017, 2018, 2019, 2020, 2021, 2022, 2023
- Total: 12 años de datos históricos

### Dimensiones Estadísticas

| Dimensión | Registros | Rango | Notas |
|-----------|-----------|-------|-------|
| Matriculados | 12 | 1992-2023 | Total anual |
| Nuevos Inscritos | 12 | 1992-2023 | Ingreso nuevo |
| Género | 12 | 1992-2023 | M/F con % |
| Estado Civil | 12 | 1992-2023 | Soltero/Casado/Otros |
| Colegio | 12 | 1992-2023 | Fiscal/Particular/Mixto |
| Laboral | 12 | 1992-2023 | Trabaja/No Trabaja/Eventual |
| Jornada | 12 | 1992-2023 | T.Completo/Medio/Eventual |
| Vivienda | 12 | 1992-2023 | 5 tipos |
| Edad | 1 | Solo 2023 | 8 rangos etarios |
| Permanencia | 12 | 1992-2023 | 8 categorías |

---

## 🛠️ Desarrollo Futuro

### Funcionalidades Pendientes
- [ ] Panel de administración (CRUD)
- [ ] Exportación a PDF (mPDF o TCPDF)
- [ ] Gráficos dinámicos en reportes
- [ ] Comparativo entre años (gráficos interactivos)
- [ ] Filtros avanzados con rango de fechas
- [ ] Análisis de tendencias automático
- [ ] API con autenticación Bearer Token
- [ ] Caché de resultados
- [ ] Auditoría de cambios

### Mejoras Técnicas
- [ ] Implementar inyección de dependencias (DI)
- [ ] Agregar validación de formularios con AJAX
- [ ] Implementar paginación en tablas grandes
- [ ] Optimizar consultas con índices adicionales
- [ ] Agregar tests unitarios con PHPUnit

---

## 📝 Patrones de Código Utilizados

### Arquitectura MVC Simple
```
Request → Router (index.php) → Controller → Model → DB
                ↓
            View (HTML)
                ↓
              Response
```

### Modelo Base Reutilizable
```php
class Model {
    public function getAll()
    public function getByAnio(int $anio)
    public function getById(int $id)
    public function getByRangeAnios(int $inicio, int $fin)
}
```

### Controladores Orquestadores
Los controladores instancian modelos y preparan datos para vistas:
```php
class DashboardController {
    public function getKPIs()
    public function getEvolucionMatriculas()
    public function getGeneroComparativo()
}
```

---

## 🐛 Solución de Problemas

### Error: "Base de datos no encontrada"
- Verificar que se ejecutó el script SQL en phpMyAdmin
- Confirmar nombre de DB: `fcpn_informatica`
- Recargar página

### Error: "PDOException"
- Verificar credenciales en `config/database.php`
- Asegurar que MySQL/MariaDB está corriendo
- Confirmar charset UTF8MB4 en DB

### Gráficos no se muestran
- Abrir consola del navegador (F12)
- Verificar que Chart.js cargó correctamente (CDN)
- Comprobar que hay datos en la BD

### Exportación CSV con caracteres extraños
- La app agrega BOM UTF-8 automáticamente
- Abrir en Excel: Archivo → Importar → UTF-8

---

## 📚 Referencias y Recursos

### Documentación Oficial
- [PHP Manual](https://www.php.net/manual/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap 5 Docs](https://getbootstrap.com/docs/)
- [Chart.js Docs](https://www.chartjs.org/docs/latest/)

### Vistas Útiles (SQL)
```sql
-- Resumen consolidado por año
SELECT * FROM v_resumen_anual WHERE anio = 2023;

-- Tendencia de crecimiento
SELECT * FROM v_crecimiento_matriculas;
```

---

## 👥 Contacto y Soporte

**Proyecto:** Digitalización FCPN Informática  
**Institución:** UMSA - La Paz, Bolivia  
**Período:** 1992-2023

Para consultas sobre los datos o la estructura de la aplicación, contactar con la FCPN.

---

## 📄 Licencia

Proyecto desarrollado para la Universidad Mayor de San Andrés (UMSA).  
Datos históricos de la Facultad de Ciencias Puras y Naturales.

---

**Última actualización:** 2024  
**Versión:** 1.0  
**Estado:** Funcional (Beta)
