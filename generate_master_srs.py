# -*- coding: utf-8 -*-
"""
Master Script untuk Mengompilasi Dokumen Word Software Requirements Specification (SRS)
Standar IEEE 830-1998 Lengkap
"""

import os
import sys

# Konfigurasi encoding UTF-8 untuk Windows terminal
if sys.platform.startswith('win'):
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except Exception:
        pass

import docx
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.oxml import OxmlElement
from docx.oxml.ns import qn

from srs_helpers import add_page_number_to_run
from srs_sections_p1 import build_cover_page, build_approval_and_meta, build_chapter_1, build_chapter_2, build_chapter_3
from srs_sections_p2 import build_chapter_4, build_chapter_5
from srs_sections_p3 import build_chapter_6, build_chapter_7, build_chapter_8, build_chapter_9

def main():
    print("Memulai inisialisasi dokumen Microsoft Word (.docx)...")
    doc = Document()

    # Konfigurasi Margin Dokumen (Standar Akademik & Rekayasa Perangkat Lunak)
    # Kiri: 3.0 cm (1.18 inch) untuk penjilidan, Kanan: 2.5 cm (0.98 inch), Atas: 2.5 cm, Bawah: 2.5 cm
    sections = doc.sections
    for section in sections:
        section.top_margin = Inches(1.0)
        section.bottom_margin = Inches(1.0)
        section.left_margin = Inches(1.2)
        section.right_margin = Inches(1.0)
        section.different_first_page_header_footer = True

        # Header Halaman
        header = section.header
        p_hdr = header.paragraphs[0]
        p_hdr.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_hdr.paragraph_format.space_after = Pt(0)
        r_hdr = p_hdr.add_run("SOFTWARE REQUIREMENTS SPECIFICATION (SRS) — SPK LAPTOP GAMING TOPSIS")
        r_hdr.font.name = "Calibri"
        r_hdr.font.size = Pt(8.5)
        r_hdr.font.color.rgb = RGBColor(148, 163, 184) # Slate 400

        # Footer Halaman
        footer = section.footer
        p_ftr = footer.paragraphs[0]
        p_ftr.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        p_ftr.paragraph_format.space_after = Pt(0)
        
        r_ftr_lbl = p_ftr.add_run("Sistem Pendukung Keputusan Berbasis Web  |  Halaman ")
        r_ftr_lbl.font.name = "Calibri"
        r_ftr_lbl.font.size = Pt(9)
        r_ftr_lbl.font.color.rgb = RGBColor(100, 116, 139)

        r_page = p_ftr.add_run()
        r_page.font.name = "Calibri"
        r_page.font.size = Pt(9)
        r_page.font.bold = True
        r_page.font.color.rgb = RGBColor(15, 44, 89)
        add_page_number_to_run(r_page)

    print("Membangun Halaman Judul (Cover Page)...")
    build_cover_page(doc)

    print("Membangun Lembar Pengesahan, Pengendalian Dokumen, & Ringkasan Eksekutif...")
    build_approval_and_meta(doc)

    print("Membangun Bab 1: Pendahuluan...")
    build_chapter_1(doc)

    print("Membangun Bab 2: Deskripsi Keseluruhan Sistem...")
    build_chapter_2(doc)

    print("Membangun Bab 3: Kebutuhan Antarmuka Eksternal...")
    build_chapter_3(doc)

    print("Membangun Bab 4: Kebutuhan Fungsional Sistem (SRS-F-01 s/d SRS-F-20)...")
    build_chapter_4(doc)

    print("Membangun Bab 5: Formulasi Matematis ROC & TOPSIS serta Simulasi Kasus...")
    build_chapter_5(doc)

    print("Membangun Bab 6: Desain Model Sistem & Kamus Data Basis Data 7 Tabel...")
    build_chapter_6(doc)

    print("Membangun Bab 7: Kebutuhan Non-Fungsional (NFR)...")
    build_chapter_7(doc)

    print("Membangun Bab 8: Matriks Ketertelusuran Kebutuhan (RTM)...")
    build_chapter_8(doc)

    print("Membangun Bab 9: Lampiran, Struktur Direktori & Penutup...")
    build_chapter_9(doc)

    output_filename = os.path.join(os.getcwd(), "SRS_SPK_PEMILIHAN_LAPTOP_GAMING_TOPSIS.docx")
    print("Menyimpan dokumen final ke SRS_SPK_PEMILIHAN_LAPTOP_GAMING_TOPSIS.docx...")
    doc.save(output_filename)
    print("Dokumen Word SRS berhasil dibuat dengan sukses dan sempurna!")

if __name__ == "__main__":
    main()
