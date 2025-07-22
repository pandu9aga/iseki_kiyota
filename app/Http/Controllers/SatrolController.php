<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Shape\Drawing\File;
use PhpOffice\PhpPresentation\Shape\RichText;

class SatrolController extends Controller
{
    // Tampilkan form ambil foto dan input
    public function form()
    {
        return view('satrol.foto_form');
    }

    // Tampilkan hasil foto dan input
    public function hasil(Request $request)
    {
        $foto = $request->input('foto'); // base64
        $teks = $request->input('teks');
        return view('satrol.foto_hasil', compact('foto', 'teks'));
    }

    // Unduh sebagai PPT
    public function unduhPpt(Request $request)
    {
        $foto = $request->input('foto');
        $teks = $request->input('teks');

        $ppt = new PhpPresentation();
        $slide = $ppt->getActiveSlide();

        // Tambah gambar dari base64
        if ($foto) {
            $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto));
            $tmpPath = tempnam(sys_get_temp_dir(), 'pptimg');
            file_put_contents($tmpPath, $imgData);
            $shape = new File();
            $shape->setName('Foto')
                ->setDescription('Foto')
                ->setPath($tmpPath)
                ->setWidth(400)
                ->setHeight(300)
                ->setOffsetX(50)
                ->setOffsetY(100);
            $slide->addShape($shape);
        }

        // Tambah teks
        $textShape = new RichText();
        $textShape->setHeight(100)
            ->setWidth(600)
            ->setOffsetX(50)
            ->setOffsetY(500);
        $textRun = $textShape->createTextRun($teks);
        $textRun->getFont()->setBold(true)->setSize(20)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Tambah teks
        $textShape = new RichText();
        $textShape->setHeight(100)
            ->setWidth(600)
            ->setOffsetX(300)
            ->setOffsetY(20);
        $textRun = $textShape->createTextRun("Hasil Temuan Patrol Safety");
        $textRun->getFont()->setBold(true)->setSize(20)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Output PPT
        $filename = 'temuan_safety.pptx';
        $writer = IOFactory::createWriter($ppt, 'PowerPoint2007');
        ob_start();
        $writer->save('php://output');
        $pptData = ob_get_clean();

        // Hapus file temp jika ada
        if (isset($tmpPath) && file_exists($tmpPath)) {
            @unlink($tmpPath);
        }

        return Response::make($pptData, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
