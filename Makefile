# Makefile para compilar el Manual de Usuario Quantum
# Uso: make (compila el manual)
#      make clean (elimina archivos temporales)
#      make clean-all (elimina todo incluyendo el PDF)

TEXFILE = manual_usuario_quantum.tex
PDFFILE = manual_usuario_quantum.pdf
LATEX = pdflatex
LATEXFLAGS = -interaction=nonstopmode

.PHONY: all clean clean-all help

# Compilación por defecto
all: $(PDFFILE)

# Regla para generar el PDF
$(PDFFILE): $(TEXFILE)
	@echo "=========================================="
	@echo "  Compilando Manual de Usuario Quantum"
	@echo "=========================================="
	@echo ""
	@echo "🔄 Primera compilación..."
	$(LATEX) $(LATEXFLAGS) $(TEXFILE) > /dev/null 2>&1 || true
	@echo "✓ Primera compilación completada"
	@echo ""
	@echo "🔄 Segunda compilación (referencias cruzadas)..."
	$(LATEX) $(LATEXFLAGS) $(TEXFILE) > /dev/null 2>&1 || true
	@echo "✓ Segunda compilación completada"
	@echo ""
	@if [ -f "$(PDFFILE)" ]; then \
		echo "=========================================="; \
		echo "  ✓ Compilación exitosa!"; \
		echo "=========================================="; \
		echo ""; \
		echo "📄 Archivo generado: $(PDFFILE)"; \
		du -h $(PDFFILE) | awk '{print "📊 Tamaño: " $$1}'; \
		echo ""; \
	else \
		echo "❌ Error: El PDF no se generó correctamente."; \
		echo "   Revise el archivo $(basename $(TEXFILE)).log"; \
		exit 1; \
	fi

# Limpiar archivos temporales
clean:
	@echo "🧹 Limpiando archivos temporales..."
	@rm -f *.aux *.log *.out *.toc *.lof *.lot *.nav *.snm *.vrb *.bbl *.blg 2>/dev/null || true
	@echo "✓ Archivos temporales eliminados"

# Limpiar todo incluyendo el PDF
clean-all: clean
	@echo "🧹 Eliminando PDF..."
	@rm -f $(PDFFILE) 2>/dev/null || true
	@echo "✓ Limpieza completa"

# Mostrar ayuda
help:
	@echo "Manual de Usuario Quantum - Makefile"
	@echo ""
	@echo "Uso:"
	@echo "  make          - Compila el manual (genera PDF)"
	@echo "  make clean    - Elimina archivos temporales"
	@echo "  make clean-all - Elimina todo incluyendo el PDF"
	@echo "  make help     - Muestra esta ayuda"
	@echo ""
	@echo "Requisitos:"
	@echo "  - pdflatex instalado"
	@echo "  - Paquetes LaTeX: babel, tikz, pgfplots, fontawesome5, tcolorbox, etc."

