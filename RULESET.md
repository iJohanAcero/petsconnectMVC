# 🚀 PetsConnect - Reglas para Contribuciones y Pushes

## 📌 **Reglas Generales**
1. **🚫 Prohibido hacer `merge` directo a `main`**
   - La rama `main` es estable y solo se actualiza con cambios revisados y aprobados.
   
2. **🌱 Creación de ramas individuales**
   - Cada colaborador debe trabajar en su propia rama con el formato:
     ```
     rama/tu-nombre
     ```
   - Ejemplo: `rama/juan-perez`

3. **🌿 Creación de sub-ramas para tareas específicas**
   - Dentro de la rama personal, las tareas deben realizarse en sub-ramas con el formato:
     ```
     rama/tu-nombre/nombre-tarea
     ```
   - Ejemplo: `rama/juan-perez/arreglo-login`
   - Una vez finalizada la tarea, la sub-rama se fusiona con la rama principal del colaborador (`rama/tu-nombre`).

## 🔄 **Proceso de Integración de Código**
1. **📥 Antes de empezar una tarea:**
   - Actualizar la rama local con los últimos cambios de `main`.
   - Usar `git pull origin main` para evitar conflictos futuros.

2. **✅ Validación antes del `merge` a `main`**
   - Todo `merge` a `main` debe ser revisado y aprobado por al menos un revisor asignado.
   - Se debe avisar al equipo antes de solicitar el `merge`.
   
3. **🚀 Pull Requests (PR)**
   - Crear un PR desde `rama/tu-nombre` hacia `main`.
   - Incluir una descripción clara de los cambios.
   - Etiquetar a los revisores responsables.
   - No fusionar el PR sin aprobación.

4. **⚠️ Conflictos y revisiones**
   - Si hay conflictos, el colaborador debe resolverlos antes de pedir la aprobación.
   - Las revisiones y cambios deben realizarse en la rama antes de ser fusionadas en `main`.

## 🛠 **Buenas Prácticas**
- **Commits claros y descriptivos** ✍️
  - Usa mensajes concisos y significativos.
  - Ejemplo:
    ```
    git commit -m "Fix: se corrigió el bug en el login cuando el usuario ingresaba sin email"
    ```
- **Mantener el código limpio y estructurado** ✅
- **Respetar el formato de código del proyecto** 📏

📢 **Siguiendo estas reglas garantizamos un flujo de trabajo ordenado y un proyecto estable.** 💪🔥
