"""Genera reference.docx: plantilla de estilos para pandoc (Times New Roman 12,
tamaños de titulo, margenes ~2.5cm, numero de pagina en el pie).
Uso: /tmp/docgen-venv/bin/python build_reference_docx.py <salida.docx>
"""
import sys
from docx import Document
from docx.shared import Pt, Cm
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml.ns import qn
from docx.oxml import OxmlElement

out_path = sys.argv[1]

doc = Document()

section = doc.sections[0]
section.page_height = Cm(27.94)
section.page_width = Cm(21.59)
section.top_margin = Cm(2.5)
section.bottom_margin = Cm(2.5)
section.left_margin = Cm(2.5)
section.right_margin = Cm(2.5)


def set_font(style, name="Times New Roman", size=12, bold=False, italic=False):
    style.font.name = name
    style.font.size = Pt(size)
    style.font.bold = bold
    style.font.italic = italic
    rpr = style.element.get_or_add_rPr()
    rfonts = rpr.find(qn("w:rFonts"))
    if rfonts is None:
        rfonts = OxmlElement("w:rFonts")
        rpr.append(rfonts)
    rfonts.set(qn("w:ascii"), name)
    rfonts.set(qn("w:hAnsi"), name)
    rfonts.set(qn("w:eastAsia"), name)
    rfonts.set(qn("w:cs"), name)


normal = doc.styles["Normal"]
set_font(normal, size=12)
normal.paragraph_format.space_after = Pt(8)
normal.paragraph_format.line_spacing = 1.15
normal.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY

title = doc.styles["Title"]
set_font(title, size=26, bold=True)
title.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.CENTER
title.paragraph_format.space_after = Pt(12)

for lvl, size in ((1, 16), (2, 14), (3, 12)):
    style = doc.styles[f"Heading {lvl}"]
    set_font(style, size=size, bold=True, italic=(lvl == 3))
    style.paragraph_format.space_before = Pt(18)
    style.paragraph_format.space_after = Pt(8)
    style.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.LEFT
    style.paragraph_format.keep_with_next = True

# Estilo usado por pandoc para el texto de las tablas
try:
    table_normal = doc.styles["Table Contents"]
    set_font(table_normal, size=11)
except KeyError:
    pass

# Pie de captura de imagen (Figura X.)
try:
    caption = doc.styles["Caption"]
    set_font(caption, size=10, italic=True, bold=False)
    caption.paragraph_format.alignment = WD_ALIGN_PARAGRAPH.CENTER
    caption.paragraph_format.space_before = Pt(4)
    caption.paragraph_format.space_after = Pt(14)
except KeyError:
    pass

# --------------------------------------------------------------
# Pie de pagina: numero de pagina centrado
# --------------------------------------------------------------
footer = section.footer
footer_para = footer.paragraphs[0]
footer_para.alignment = WD_ALIGN_PARAGRAPH.CENTER
run = footer_para.add_run()
fld_begin = OxmlElement("w:fldChar")
fld_begin.set(qn("w:fldCharType"), "begin")
instr = OxmlElement("w:instrText")
instr.set(qn("xml:space"), "preserve")
instr.text = " PAGE "
fld_sep = OxmlElement("w:fldChar")
fld_sep.set(qn("w:fldCharType"), "separate")
fld_end = OxmlElement("w:fldChar")
fld_end.set(qn("w:fldCharType"), "end")
run._r.append(fld_begin)
run._r.append(instr)
run._r.append(fld_sep)
run._r.append(fld_end)
set_font(footer_para.style, size=10)

doc.save(out_path)
print(f"reference.docx generado en {out_path}")
