<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\DocumentLayout;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Shape\Drawing\File;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Fill;
use PhpOffice\PhpPresentation\Style\Shadow;
use PhpOffice\PhpPresentation\Shape\AutoShape;
use PhpOffice\PhpPresentation\Style\Border;

class KiyotaController extends Controller
{
    // Tampilkan form ambil foto dan input
    public function form()
    {
        return view('kiyota.foto_form');
    }

    // Tampilkan hasil foto dan input
    public function hasil(Request $request)
    {
        $foto = $request->input('foto'); // base64
        $judul = $request->input('judul');
        $team = $request->input('team');
        $pic = $request->input('pic');
        $keterangan = $request->input('keterangan');
        $penanganan = $request->input('penanganan');
        return view('kiyota.foto_hasil', compact('foto', 'judul', 'team', 'pic', 'keterangan', 'penanganan'));
    }

    public function unduhPpt(Request $request)
    {
        $foto = $request->input('foto');
        $team = $request->input('team');
        $judul = $request->input('judul');
        $pic = $request->input('pic');
        $keterangan = $request->input('keterangan');
        $penanganan = $request->input('penanganan');

        $ppt = new PhpPresentation();
        // Set layout ke 16:9
        $ppt->getLayout()->setDocumentLayout(DocumentLayout::LAYOUT_CUSTOM, true)
            ->setCX( 1280,  DocumentLayout::UNIT_PIXEL)
            ->setCY( 720,  DocumentLayout::UNIT_PIXEL);
        $slide = $ppt->getActiveSlide();

        $backgroundPath = public_path('assets/bg-kyt.png');
        if (file_exists($backgroundPath)) {
            $shape = new File();
            $shape->setName('Background')
                ->setDescription('Background')
                ->setPath($backgroundPath)
                ->setWidth(1280)
                ->setHeight(720)
                ->setOffsetX(0)
                ->setOffsetY(0);
            $slide->addShape($shape);
        }

        // Tambah gambar dari base64
        if ($foto) {
            $imgData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $foto));
            $tmpPath = tempnam(sys_get_temp_dir(), 'pptimg');
            file_put_contents($tmpPath, $imgData);

            // Ambil ukuran asli gambar
            list($origWidth, $origHeight) = getimagesize($tmpPath);

            // Maksimal ukuran
            $maxWidth = 680;
            $maxHeight = 454;

            // Hitung rasio
            $widthRatio = $maxWidth / $origWidth;
            $heightRatio = $maxHeight / $origHeight;
            $scale = min($widthRatio, $heightRatio); // untuk memperbesar hingga menyentuh batas

            // Ukuran akhir
            $finalWidth = $origWidth * $scale;
            $finalHeight = $origHeight * $scale;

            // Offset agar gambar di tengah (dengan asumsi slide 1280x720)
            $offsetX = ((1280 - $finalWidth) / 2) - 250;
            $offsetY = ((720 - $finalHeight) / 2) + 30;

            // Buat shape gambar
            $shape = new File();
            $shape->setName('Foto')
                ->setDescription('Foto')
                ->setPath($tmpPath)
                ->setWidth($finalWidth)
                ->setHeight($finalHeight)
                ->setOffsetX($offsetX)
                ->setOffsetY($offsetY);

            $slide->addShape($shape);
        }

        // Tambah teks KYT
        $textShape = new RichText();
        $textShape->setHeight(10)
            ->setWidth(650)
            ->setOffsetX(80)
            ->setOffsetY(0);
        $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $textShape->createTextRun("KIKEN YOCHI TRAINING (KYT)");
        $textRun->getFont()->setBold(true)->setSize(36)->setColor(new Color('FFFF0000'));
        $shadow = $textShape->getShadow();
        $shadow->setVisible(true);
        $shadow->setDirection(45);
        $shadow->setDistance(3);
        $shadow->setBlurRadius(0);
        $shadow->setAlpha(100);
        $shadow->getColor()->setRGB('5b9ad5');
        $slide->addShape($textShape);

        // Tambah teks team
        $textShape = new RichText();
        $textShape->setHeight(10)
            ->setWidth(530)
            ->setOffsetX(750)
            ->setOffsetY(0);
        $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $textShape->createTextRun($team);
        $textRun->getFont()->setBold(true)->setSize(36)->setColor(new Color('FF404040'));
        $shadow = $textShape->getShadow();
        $shadow->setVisible(true);
        $shadow->setDirection(45);
        $shadow->setDistance(2.5);
        $shadow->setBlurRadius(3);
        $shadow->setAlpha(40);
        $shadow->getColor()->setRGB('000000');
        $slide->addShape($textShape);

        // Tambah shape sebagai background judul
        $bgShape = new AutoShape();
        $bgShape->setType(AutoShape::TYPE_ROUNDED_RECTANGLE);
        $bgShape->setHeight(50)
            ->setWidth(680)
            ->setOffsetX(50)
            ->setOffsetY(80);
        $bgFill = $bgShape->getFill();
        $bgFill->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color('FFFFFF00'));
        $bgOutline = $bgShape->getOutline();
        $bgOutline->setWidth(0)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->setStartColor(new Color(Color::COLOR_BLACK));
        $slide->addShape($bgShape);

        // Tambah teks judul
        $textShape = new RichText();
        $textShape->setHeight(50)
            ->setWidth(680)
            ->setOffsetX(50)
            ->setOffsetY(80);
        $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textShape->getActiveParagraph()->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $textRun = $textShape->createTextRun(strtoupper($judul));
        $textRun->getFont()->setBold(true)->setSize(18)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Tambah teks pic
        $textShape = new RichText();
        $textShape->setHeight(35)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(100);
        $textRun = $textShape->createTextRun("DISAMPAIKAN OLEH :");
        $textRun->getFont()->setBold(true)->setSize(17)->setColor(new Color('FFFF0000'));
        $slide->addShape($textShape);
        // Tambah teks pic
        $textShape = new RichText();
        $textShape->setHeight(40)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(125);
        $textRun = $textShape->createTextRun($pic);
        $textRun->getFont()->setSize(17)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Tambah teks keterangan
        $textShape = new RichText();
        $textShape->setHeight(35)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(180);
        $textRun = $textShape->createTextRun("POTENSI BAHAYA :");
        $textRun->getFont()->setBold(true)->setSize(17)->setColor(new Color('FFFF0000'));
        $slide->addShape($textShape);
        // Tambah teks keterangan
        $textShape = new RichText();
        $textShape->setHeight(150)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(205);
        $textRun = $textShape->createTextRun($keterangan);
        $textRun->getFont()->setSize(17)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Tambah teks penanganan
        $textShape = new RichText();
        $textShape->setHeight(35)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(380);
        $textRun = $textShape->createTextRun("PENANGANAN :");
        $textRun->getFont()->setBold(true)->setSize(17)->setColor(new Color('FFFF0000'));
        $slide->addShape($textShape);
        // Tambah teks penanganan
        $textShape = new RichText();
        $textShape->setHeight(150)
            ->setWidth(430)
            ->setOffsetX(800)
            ->setOffsetY(405);
        $textRun = $textShape->createTextRun($penanganan);
        $textRun->getFont()->setSize(17)->setColor(new Color('FF000000'));
        $slide->addShape($textShape);

        // Tambah teks
        $textShape = new RichText();
        $textShape->setHeight(60)
            ->setWidth(400)
            ->setOffsetX(780)
            ->setOffsetY(650);
        $textShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        Carbon::setLocale('id');
        $textRun = $textShape->createTextRun(Carbon::parse(date('Y-m-d'))->translatedFormat('d F Y'));
        $textRun->getFont()->setBold(true)->setSize(32)->setColor(new Color('FF2F5597'));
        $shadow = $textShape->getShadow();
        $shadow->setVisible(true);
        $shadow->setDirection(45);
        $shadow->setDistance(2.5);
        $shadow->setBlurRadius(3);
        $shadow->setAlpha(40);
        $shadow->getColor()->setRGB('000000');
        $slide->addShape($textShape);

        // Output PPT
        $filename = 'kyt.pptx';
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

    public function unduhTes(Request $request)
    {
        $presentation = new PhpPresentation();

        // Create slide
        $currentSlide = $presentation->getActiveSlide();

        // Create a shape (text)
        $shape = $currentSlide->createRichTextShape()
                ->setHeight(300)
                ->setWidth(600)
                ->setOffsetX(170)
                ->setOffsetY(180);
        $shape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $shape->createTextRun('Thank you for using PHPPresentation!');
        $textRun->getFont()->setBold(true)
                            ->setSize(60)
                            ->setColor(new Color('FFE06B20'));

        $writerPPTX = IOFactory::createWriter($presentation, 'PowerPoint2007');
        $writerPPTX->save(__DIR__ . '/sample.pptx');
    }
}
